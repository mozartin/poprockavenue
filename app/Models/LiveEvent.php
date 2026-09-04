<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LiveEvent extends Model
{
    use HasTranslations;

    public array $translatable = [
        'title',
        'description',
        'ticket_info',
    ];

    protected $fillable = [
        'slug',
        'title',
        'description',
        'ticket_info',
        'venue_name',
        'venue_address',
        'city',
        'starts_at',
        'poster_path',
        'info_url',
        'ticket_url',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where('starts_at', '>=', now()->startOfDay());
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('starts_at')->orderBy('sort_order');
    }

    public function posterUrl(): ?string
    {
        if (! filled($this->poster_path)) {
            return null;
        }

        return MediaPath::url($this->poster_path);
    }

    public function primaryUrl(): ?string
    {
        return $this->ticket_url ?: $this->info_url;
    }
}
