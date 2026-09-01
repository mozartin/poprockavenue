<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RepertoireSong extends Model
{
    protected $fillable = [
        'repertoire_category_id',
        'artist',
        'title',
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

    public function category(): BelongsTo
    {
        return $this->belongsTo(RepertoireCategory::class, 'repertoire_category_id');
    }

    public function displayName(): string
    {
        return $this->title ? "{$this->artist} — {$this->title}" : $this->artist;
    }
}
