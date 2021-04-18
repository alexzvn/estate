<?php

namespace App\Http\Requests\Manager\Customer;

use App\Repository\User;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.customer.modify');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $updateUser = $this->getUpdateUser();

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignoreModel($updateUser)
            ],
            'phone' => [
                'required',
                'regex:/^[0-9.]+$/',
                Rule::unique('users', 'phone')->ignoreModel($updateUser)
            ],
            'provinces' => 'nullable|array',
            'provinces.*' => 'string|exists:provinces,id',
            'name' => 'required|string',
            'password' => 'nullable|string',
            'password_confirm' => 'required_with:password|same:password'
        ];
    }

    protected function getUpdateUser()
    {
        $id = $this->route()->parameters['id'] ?? '';

        return $this->updateUser = User::findOrFail($id);
    }
}
