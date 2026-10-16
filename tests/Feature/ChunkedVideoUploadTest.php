<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ChunkedVideoUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_upload_chunks(): void
    {
        $response = $this->postJson(route('admin.chunked-video-upload'), [
            'upload_id' => (string) Str::uuid(),
            'chunk_index' => 0,
            'total_chunks' => 1,
            'file_name' => 'clip.mp4',
            'directory' => 'uploads/moments/videos',
            'chunk' => UploadedFile::fake()->create('clip.mp4', 100, 'video/mp4'),
        ]);

        $response->assertUnauthorized();
    }

    public function test_authenticated_user_can_upload_video_in_chunks(): void
    {
        Storage::fake('local');
        Storage::fake('public');

        $user = User::factory()->create();
        $uploadId = (string) Str::uuid();

        $chunkOne = UploadedFile::fake()->createWithContent('clip.mp4', str_repeat('a', 1024));
        $chunkTwo = UploadedFile::fake()->createWithContent('clip.mp4', str_repeat('b', 1024));

        $this->actingAs($user)
            ->postJson(route('admin.chunked-video-upload'), [
                'upload_id' => $uploadId,
                'chunk_index' => 0,
                'total_chunks' => 2,
                'file_name' => 'clip.mp4',
                'directory' => 'uploads/moments/videos',
                'chunk' => $chunkOne,
            ])
            ->assertOk()
            ->assertJson(['done' => false]);

        $response = $this->actingAs($user)
            ->postJson(route('admin.chunked-video-upload'), [
                'upload_id' => $uploadId,
                'chunk_index' => 1,
                'total_chunks' => 2,
                'file_name' => 'clip.mp4',
                'directory' => 'uploads/moments/videos',
                'chunk' => $chunkTwo,
            ])
            ->assertOk()
            ->assertJson(['done' => true]);

        $path = $response->json('path');

        $this->assertIsString($path);
        $this->assertStringStartsWith('uploads/moments/videos/', $path);
        Storage::disk('public')->assertExists($path);
        $this->assertSame(2048, Storage::disk('public')->size($path));
    }

    public function test_rejects_disallowed_directory(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson(route('admin.chunked-video-upload'), [
                'upload_id' => (string) Str::uuid(),
                'chunk_index' => 0,
                'total_chunks' => 1,
                'file_name' => 'clip.mp4',
                'directory' => 'uploads/evil',
                'chunk' => UploadedFile::fake()->create('clip.mp4', 100, 'video/mp4'),
            ])
            ->assertStatus(422);
    }
}
