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
        return $this->user() && $this->user()->can('manager.post.create');
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
            'title'    => 'required',
            'phone'    => 'required|regex:/^[0-9_.]+$/',
            'price'    => 'required|regex:/^[0-9,.]+$/',
            'category' => 'required|exists:categories,_id',
            'province' => 'nullable|exists:provinces,_id',
            'district' => 'nullable|exists:district,_id',
            'type'     => [
                'nullable',
                Rule::in(PostType::getValues())
            ],
            'status'   => [
                'required',
                Rule::in(PostStatus::getValues())
            ]
        ];
    }
}
