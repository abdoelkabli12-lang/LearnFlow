<?php

namespace App\Http\Requests;

use App\Models\Module;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $module = $this->route('module');

        return $module instanceof Module
            && (bool) $this->user()?->can('update', $module->course);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'type'         => ['required', 'in:video,document,text'],
            'duration'     => ['nullable', 'integer', 'min:1'],
            'is_free'      => ['boolean'],
            'content_url'  => ['required_if:type,video', 'nullable', 'url'],
            'content_file' => ['required_if:type,document', 'nullable', 'file', 'mimes:pdf,doc,docx', 'max:10240'],
            'content_text' => ['required_if:type,text', 'nullable', 'string', 'max:255'],
        ];
    }
}
