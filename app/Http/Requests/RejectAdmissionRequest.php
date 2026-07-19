<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('admissions.process') ?? false;
    }

    public function rules(): array
    {
        return [
            'rejection_reason' => ['required', 'string', 'max:500'],
        ];
    }
}
