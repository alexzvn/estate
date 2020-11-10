<?php

namespace App\Http\Requests\Manager\Role;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRole extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.role.modify');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => "required|string|unique:roles,name,$this->name,name",
            'for_customer' => 'nullable|boolean',
            'permissions'  => 'array|exists:permissions,id'
        ];
    }
}
