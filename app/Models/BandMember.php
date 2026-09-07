<?php

namespace App\Models;

use App\Support\MediaPath;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BandMember extends Model
{
    use HasTranslations;

    public array $translatable = ['role', 'bio'];

    protected $fillable = [
        'name',
        'role',
        'bio',
        'image',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function imageUrl(): ?string
    {
        if (! filled($this->image)) {
            return null;
        }

        return MediaPath::url($this->image);
    }
}
