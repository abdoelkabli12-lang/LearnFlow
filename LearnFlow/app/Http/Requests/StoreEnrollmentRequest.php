<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => [
                'required',
                'integer',
                'exists:courses,id',
                Rule::unique('enrollments')->where(function ($query) {
                    return $query
                        ->where('user_id', $this->user()?->id)
                        ->whereIn('status', ['pending', 'accepted']);
                }),
            ],
            'enrolled_at' => ['nullable', 'date', 'before_or_equal:today'],
            'status' => ['nullable', Rule::in(['pending', 'accepted', 'cancelled'])],
            'progress' => ['nullable', 'integer', 'between:0,100'],
            'user_id' => ['prohibited'],
        ];
    }
}
