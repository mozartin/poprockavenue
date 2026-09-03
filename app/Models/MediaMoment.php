<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MediaMoment extends Model
{
    use HasTranslations;

    public array $translatable = ['title'];

    protected $fillable = [
        'title',
        'video_path',
        'poster_path',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function videoUrl(): string
    {
        return MediaPath::url($this->video_path);
    }

    public function posterUrl(): ?string
    {
        if (! filled($this->poster_path)) {
            return null;
        }

        return MediaPath::url($this->poster_path);
    }
}
