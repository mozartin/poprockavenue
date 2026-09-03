<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class EventType extends Model
{
    use HasTranslations;

    public array $translatable = [
        'name',
        'title',
        'subtitle',
        'description',
        'content',
        'meta_title',
        'meta_description',
    ];

    protected $fillable = [
        'name',
        'slug',
        'title',
        'subtitle',
        'description',
        'content',
        'image',
        'accent_color',
        'hero_image',
        'meta_title',
        'meta_description',
        'is_featured',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
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

    public function imageUrl(): string
    {
        return self::resolveImage($this->image, 'images/events/default.jpg');
    }

    public function heroImageUrl(): string
    {
        return self::resolveImage($this->hero_image ?? $this->image, 'images/hero.jpg');
    }

    protected static function resolveImage(?string $path, string $default): string
    {
        return \App\Support\MediaPath::url($path, $default);
    }
}
