<?php

namespace App\Http\Requests;

use App\Helpers\PermissionHelper;
use App\Models\Group;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return PermissionHelper::userIsOwnerOfModal($this->route('group'), $this->user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return Group::rules();
    }
}
