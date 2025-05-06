<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class ImportRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'importSource' => 'required|in:json,html',
            'importOptions' => 'required|array',
            'importOptions.*' => 'string',
            'importFile' => [
                'required',
            ],
        ];

        switch ($this->importSource) {
            case 'json':
                $rules['importFile'][] = File::types('json');
                break;
            case 'html':
                $rules['importFile'][] = File::types('html');
                break;
        }

        return $rules;
    }
}
