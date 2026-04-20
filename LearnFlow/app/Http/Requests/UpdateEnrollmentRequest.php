<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $enrollment = $this->route('enrollment');

        return $enrollment
            ? (bool) $this->user()?->can('update', $enrollment)
            : (bool) $this->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'enrolled_at' => ['sometimes', 'nullable', 'date', 'before_or_equal:today'],
            'status' => ['sometimes', Rule::in(['pending', 'accepted', 'cancelled'])],
            'progress' => ['sometimes', 'integer', 'between:0,100'],
            'user_id' => ['prohibited'],
            'course_id' => ['prohibited'],
        ];
    }
}
