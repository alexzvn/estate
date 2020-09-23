<?php

namespace App\Http\Requests\Manager\Post\Market;

use App\Enums\PostType;
use App\Enums\PostStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePost extends FormRequest
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
            'title'     => 'required',
            'phone'     => 'required|regex:/^[0-9_.]+$/',
            'category_ids'  => 'required|exists:categories,_id',
            'province'  => 'required|exists:provinces,_id',
            'district'  => 'required|exists:districts,_id',
            'images'    => 'nullable',
            'images.*'   => 'image|mimes:jpeg,png,jpg,gif|max:8192',
            'image_ids' => 'nullable|array|exists:files,_id',
            'type'      => [
                'nullable',
                Rule::in(PostType::getValues())
            ],
            // 'status'    => [
            //     'required',
            //     Rule::in(PostStatus::getValues())
            // ]
        ];
    }
}
