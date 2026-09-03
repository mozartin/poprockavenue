<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'event_type' => ['required', 'string', 'max:100'],
            'event_date' => ['nullable', 'date', 'after_or_equal:today'],
            'location' => ['nullable', 'string', 'max:255'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:50000'],
            'message' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.max' => __('site.validation.spam'),
            'event_date.after_or_equal' => __('site.validation.event_date_future'),
        ];
    }

    public function attributes(): array
    {
        return [
            'event_type' => 'event type',
            'event_date' => 'event date',
        ];
    }
}
