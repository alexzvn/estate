<?php

namespace App\Http\Requests\Customer\Post;

use Illuminate\Foundation\Http\FormRequest;

class StorePost extends FormRequest
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
            'title' => 'required|string',
            'content' => 'required|string',
            'phone' => 'required|string|regex:/^[0-9]+$/',
            'price' => 'required|numeric',
            'category' => 'nullable|string|exists:categories,id'
        ];
    }
}
