<?php

namespace App\Http\Requests\Manager\Customer;

use Illuminate\Foundation\Http\FormRequest;

class AssignCustomer extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.user.assign.customer');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'supporter_id' => 'nullable|exists:users,_id'
        ];
    }
}
