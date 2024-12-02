<?php

namespace App\Http\Requests;

use App\Models\Group;
use App\Models\Link;
use App\Models\Tag;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreLinkApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Request::user()->tokenCan('create');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'title' => $this->title ?: null,
            'link' => filter_var($this->link, FILTER_VALIDATE_URL) ? $this->link : "https://$this->link",
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return array_merge(Link::rules($this->request->get('link')), [
            'groups' => 'nullable|array',
            'groups.*' => [
                'integer',
                'exists:groups,id',
                function (string $attribute, int $value, Closure $fail) {
                    $group = Group::find($value);
                    if ($group && $group->user_id !== auth()->id()) {
                        $fail('You do not own this group.');
                    }
                }
            ],
            'tags' => 'nullable|array',
            'tags.*' => [
                'integer',
                'exists:tags,id',
                function (string $attribute, int $value, Closure $fail) {
                    $tag = Tag::find($value);
                    if ($tag && $tag->user_id !== auth()->id()) {
                        $fail('You do not own this tag.');
                    }
                }
            ],
        ]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors()
        ], 422));
    }
}
