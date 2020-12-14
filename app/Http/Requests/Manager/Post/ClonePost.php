<?php

namespace App\Http\Requests\Manager\Post;

use App\Enums\PostType;
use App\Enums\PostStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ClonePost extends FormRequest
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
            'content'   => 'required',
            'title'     => 'required',
            'phone'     => 'required|regex:/^[0-9_.]+$/',
            'price'     => 'required|regex:/^[0-9,.]+$/',
            'category_ids'  => 'required|exists:categories,_id',
            'province'  => 'nullable|exists:provinces,_id',
            'commission'=> 'required|string|max:20',
            'district'  => 'nullable|exists:districts,_id',
            'images'    => 'nullable',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif',
            'image_ids' => 'nullable|array|exists:files,_id',
            'type'      => [
                'nullable',
                Rule::in(PostType::getValues())
            ],
        ];
    }
}
