<?php

namespace App\Http\Requests\Manager\User;

use App\Repository\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUser extends FormRequest
{
    /**
     * Undocumented function
     *
     * @var \App\Models\User
     */
    public $updateUser;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.staff.modify');
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
            'name' => 'required',
            'roles' => 'nullable|array|exists:roles,id',
            'password' => 'nullable',
            'password_confirm' => 'required_with:password|same:password'
        ];
    }

    protected function getUpdateUser()
    {
        $id = $this->route()->parameters['id'] ?? '';

        return $this->updateUser = User::findOrFail($id);
    }
}
