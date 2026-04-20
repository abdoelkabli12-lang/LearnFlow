<?php

namespace App\Http\Requests;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateModuleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $course = $this->route('course');
        $module = $this->route('module');

        if (! $module instanceof Module || ! $course instanceof Course || $module->course_id !== $course->id) {
            return false;
        }

        return (bool) $this->user()?->can('update', $module);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'duration' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
