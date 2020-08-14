<?php

namespace App\Http\Requests\Manager\Censorship\Blacklist;

use Illuminate\Foundation\Http\FormRequest;

class AddWhitelist extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('blacklist.phone.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|string|min:10|max:10|alpha_num|unique:whitelists,phone'
        ];
    }
}
