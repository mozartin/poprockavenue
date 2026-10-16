<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ChunkedVideoUploadController extends Controller
{
    private const CHUNK_MAX_BYTES = 2 * 1024 * 1024;

    private const TOTAL_MAX_BYTES = 1024 * 1024 * 1024;

    private const ALLOWED_DIRECTORIES = [
        'uploads/moments/videos',
    ];

    private const ALLOWED_EXTENSIONS = [
        'mp4',
        'mov',
        'webm',
        'm4v',
    ];

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'upload_id' => ['required', 'uuid'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:1024'],
            'file_name' => ['required', 'string', 'max:255'],
            'directory' => ['required', 'string'],
            'chunk' => ['required', 'file', 'max:2048'],
        ]);

        $directory = trim($validated['directory'], '/');

        if (! in_array($directory, self::ALLOWED_DIRECTORIES, true)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid upload directory.');
        }

        $extension = strtolower(pathinfo($validated['file_name'], PATHINFO_EXTENSION));

        if (! in_array($extension, self::ALLOWED_EXTENSIONS, true)) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Unsupported video type.');
        }

        $chunk = $request->file('chunk');

        if ($chunk === null || $chunk->getSize() > self::CHUNK_MAX_BYTES) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Chunk too large.');
        }

        $chunkIndex = (int) $validated['chunk_index'];
        $totalChunks = (int) $validated['total_chunks'];

        if ($chunkIndex >= $totalChunks) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Invalid chunk index.');
        }

        $estimatedTotal = $totalChunks * self::CHUNK_MAX_BYTES;

        if ($estimatedTotal > self::TOTAL_MAX_BYTES + self::CHUNK_MAX_BYTES) {
            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'File exceeds 1GB limit.');
        }

        $partsDirectory = 'chunked-uploads/'.$validated['upload_id'];
        $partPath = $partsDirectory.'/'.$chunkIndex.'.part';

        Storage::disk('local')->put($partPath, file_get_contents($chunk->getRealPath()));

        if ($chunkIndex < $totalChunks - 1) {
            return response()->json([
                'done' => false,
                'received' => $chunkIndex,
            ]);
        }

        for ($i = 0; $i < $totalChunks; $i++) {
            if (! Storage::disk('local')->exists($partsDirectory.'/'.$i.'.part')) {
                Storage::disk('local')->deleteDirectory($partsDirectory);

                abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Missing chunk '.$i.'.');
            }
        }

        $finalName = Str::uuid()->toString().'.'.$extension;
        $finalPath = $directory.'/'.$finalName;
        $absoluteFinal = Storage::disk('public')->path($finalPath);

        File::ensureDirectoryExists(dirname($absoluteFinal));

        $out = fopen($absoluteFinal, 'wb');

        if ($out === false) {
            abort(Response::HTTP_INTERNAL_SERVER_ERROR, 'Could not write video file.');
        }

        $totalBytes = 0;

        try {
            for ($i = 0; $i < $totalChunks; $i++) {
                $partAbsolute = Storage::disk('local')->path($partsDirectory.'/'.$i.'.part');
                $in = fopen($partAbsolute, 'rb');

                if ($in === false) {
                    throw new \RuntimeException('Could not read chunk '.$i);
                }

                while (! feof($in)) {
                    $buffer = fread($in, 1024 * 1024);

                    if ($buffer === false) {
                        fclose($in);
                        throw new \RuntimeException('Could not read chunk data '.$i);
                    }

                    $totalBytes += strlen($buffer);
                    fwrite($out, $buffer);
                }

                fclose($in);
            }
        } catch (\Throwable $e) {
            fclose($out);
            @unlink($absoluteFinal);
            Storage::disk('local')->deleteDirectory($partsDirectory);

            abort(Response::HTTP_INTERNAL_SERVER_ERROR, $e->getMessage());
        }

        fclose($out);
        Storage::disk('local')->deleteDirectory($partsDirectory);

        if ($totalBytes === 0 || $totalBytes > self::TOTAL_MAX_BYTES) {
            Storage::disk('public')->delete($finalPath);

            abort(Response::HTTP_UNPROCESSABLE_ENTITY, 'Assembled file is empty or too large.');
        }

        return response()->json([
            'done' => true,
            'path' => $finalPath,
            'bytes' => $totalBytes,
        ]);
    }
}
