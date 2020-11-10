<?php

namespace App\Http\Requests\Manager\Post;

use App\Enums\PostStatus;
use App\Enums\PostType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'content' => 'required',
            'title'    => 'required',
            'phone'    => 'required|regex:/^[0-9_.]+$/',
            'price'    => 'required|regex:/^[0-9,.]+$/',
            'category_ids' => 'required|exists:categories,id',
            'province' => 'nullable|exists:provinces,id',
            'district' => 'nullable|exists:districts,id',
            'images'    => 'nullable',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif|max:4096',
            'type'     => [
                'nullable',
                Rule::in(PostType::getValues())
            ],
        ];
    }
}
