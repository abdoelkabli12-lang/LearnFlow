<?php

namespace App\Http\Requests;

use App\Models\Lesson;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $lesson = $this->route('lesson');

        return $lesson instanceof Lesson
            && (bool) $this->user()?->can('update', $lesson);
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
            'type' => ['required', 'in:video,document,text'],
            'duration' => ['nullable', 'integer', 'min:1'],
            'is_free' => ['boolean'],
            'content_url' => ['required_if:type,video', 'nullable', 'url'],
            'content_file' => ['required_if:type,document', 'nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'content_text' => ['required_if:type,text', 'nullable', 'string', 'max:255'],
        ];
    }
}
