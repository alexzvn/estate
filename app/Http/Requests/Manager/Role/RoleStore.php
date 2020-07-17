<?php

namespace App\Http\Requests\Manager\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:roles,name',
            'for_customer' => 'nullable|boolean',
            'permissions' => 'array|exists:permissions,_id'
        ];
    }
}
