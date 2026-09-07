<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class MenuItem extends Model
{
    use HasTranslations;

    public array $translatable = ['label'];

    protected $fillable = [
        'menu_section_id',
        'label',
        'link_type',
        'link_value',
        'anchor',
        'open_in_new_tab',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'open_in_new_tab' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(MenuSection::class, 'menu_section_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    public function href(): string
    {
        $anchor = filled($this->anchor) ? $this->anchor : '';

        if ($this->link_type === 'url') {
            return $this->link_value.$anchor;
        }

        try {
            return localized_route($this->link_value).$anchor;
        } catch (\Throwable) {
            return url('/').$anchor;
        }
    }
}
