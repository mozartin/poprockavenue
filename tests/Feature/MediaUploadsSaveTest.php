<?php

namespace Tests\Feature;

use App\Filament\Resources\BandMemberResource\Pages\EditBandMember;
use App\Models\BandMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class MediaUploadsSaveTest extends TestCase
{
    use RefreshDatabase;

    public function test_band_member_photo_path_is_persisted_to_the_database(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $member = BandMember::query()->create([
            'name' => 'Test Member',
            'role' => ['en' => 'Vocals', 'nl' => 'Zang', 'uk' => 'Вокал', 'ru' => 'Вокал'],
            'bio' => ['en' => '', 'nl' => '', 'uk' => '', 'ru' => ''],
            'image' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $upload = UploadedFile::fake()->image('portrait.jpg', 800, 600);

        $this->actingAs($user);

        Livewire::test(EditBandMember::class, ['record' => $member->getKey()])
            ->fillForm([
                'name' => 'Test Member',
                'image' => [$upload],
                'role_en' => 'Vocals',
                'role_nl' => 'Zang',
                'role_uk' => 'Вокал',
                'role_ru' => 'Вокал',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $member->refresh();

        $this->assertNotNull($member->image);
        $this->assertStringStartsWith('uploads/band/', $member->image);
        Storage::disk('public')->assertExists($member->image);
    }

    public function test_saving_without_a_new_photo_keeps_the_existing_path(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('uploads/band/existing.jpg', 'fake-image');

        $user = User::factory()->create();
        $member = BandMember::query()->create([
            'name' => 'Test Member',
            'role' => ['en' => 'Vocals', 'nl' => 'Zang', 'uk' => 'Вокал', 'ru' => 'Вокал'],
            'bio' => ['en' => '', 'nl' => '', 'uk' => '', 'ru' => ''],
            'image' => 'uploads/band/existing.jpg',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditBandMember::class, ['record' => $member->getKey()])
            ->fillForm([
                'name' => 'Renamed Member',
                'role_en' => 'Vocals',
                'role_nl' => 'Zang',
                'role_uk' => 'Вокал',
                'role_ru' => 'Вокал',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $member->refresh();

        $this->assertSame('uploads/band/existing.jpg', $member->image);
        $this->assertSame('Renamed Member', $member->name);
    }

    public function test_heic_file_is_converted_to_a_usable_jpeg(): void
    {
        $fixture = base_path('public/images/IMG_7520.HEIC');

        if (! is_file($fixture)) {
            $this->markTestSkipped('HEIC fixture not present.');
        }

        Storage::fake('public');
        Storage::disk('public')->put('uploads/band/sample.heic', file_get_contents($fixture));

        $path = \App\Support\OptimizeUploadedImage::optimize('uploads/band/sample.heic');

        $this->assertStringEndsWith('.jpg', $path);
        Storage::disk('public')->assertExists($path);
        Storage::disk('public')->assertMissing('uploads/band/sample.heic');
        $this->assertLessThanOrEqual(512_000, Storage::disk('public')->size($path));
        $this->assertTrue(\App\Support\MediaPath::isUsablePublicFile($path));
    }
}
