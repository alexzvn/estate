<?php

namespace App\Http\Requests\Manager\Post;

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
        return $this->user() && $this->user()->can('manager.post.modify');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'post_content' => 'required',
            'title'     => 'required',
            'phone'     => 'required|regex:/^[0-9_.]+$/',
            'price'     => 'required|regex:/^[0-9,.]+$/',
            'category'  => 'required|exists:categories,_id',
            'province'  => 'nullable|exists:provinces,_id',
            'district'  => 'nullable|exists:districts,_id',
            'images'    => 'nullable|array|image',
            'image_ids' => 'nullable|array|exists:files,_id',
            'type'      => [
                'nullable',
                Rule::in(PostType::getValues())
            ],
            'status'    => [
                'required',
                Rule::in(PostStatus::getValues())
            ]
        ];
    }
}
