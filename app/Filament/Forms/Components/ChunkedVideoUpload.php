<?php

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class ChunkedVideoUpload extends Field
{
    protected string $view = 'filament.forms.components.chunked-video-upload';

    protected string $directory = 'uploads/moments/videos';

    protected int $chunkSizeBytes = 2 * 1024 * 1024;

    protected int $maxSizeBytes = 1024 * 1024 * 1024;

    public function directory(string $directory): static
    {
        $this->directory = trim($directory, '/');

        return $this;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function getChunkSizeBytes(): int
    {
        return $this->chunkSizeBytes;
    }

    public function getMaxSizeBytes(): int
    {
        return $this->maxSizeBytes;
    }

    public function getUploadUrl(): string
    {
        return route('admin.chunked-video-upload');
    }
}
