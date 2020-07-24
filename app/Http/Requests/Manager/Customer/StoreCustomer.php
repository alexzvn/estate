<?php

namespace App\Http\Requests\Manager\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.customer.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'password' => 'nullable',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^[0-9.]+$/|unique:users,phone',
            'password' => 'required|string',
            'password_confirm' => 'required_with:password|same:password'
        ];
    }
}
