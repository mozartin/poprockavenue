<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'event_type',
        'event_date',
        'location',
        'guests',
        'budget',
        'message',
        'status',
        'notes',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'status' => BookingStatus::class,
        ];
    }
}
