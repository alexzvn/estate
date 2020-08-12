<?php

namespace App\Http\Requests\Manager\Blacklist\Phone;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePhone extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('blacklist.phone.modify');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'note' => 'nullable|string|max:200'
        ];
    }
}
