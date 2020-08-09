<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInfo extends FormRequest
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
            'name' => 'required|string|max:50',
            'email' => ['required','string','email', Rule::unique('users', 'email')->ignoreModel($this->user())],
            'address' => 'nullable|string|max:100',
            'birthday' => 'nullable|date_format:Y-m-d',
            'password' => 'nullable|string|max:100|min:8',
            'password_old' => 'nullable|required_with:password|string',
            'password_confirm' => 'required_with:password|same:password'
        ];
    }

    public function attributes()
    {
        return [
            'password_old' => 'mật khẩu cũ',
            'password'     => 'mật khẩu mới',
            'password_confirm' => 'mật khẩu nhập lại'
        ];
    }
}
