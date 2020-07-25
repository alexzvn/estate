<?php

namespace App\Http\Requests\Manager\Plan;

use App\Enums\PostType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.plan.create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|unique:plans,name',
            'price' => 'nullable|string|regex:/^[0-9,.]+$/',
            'post_type' => ['nullable', 'array', Rule::in(PostType::getValues())],
            'categories' => 'nullable|array|exists:categories,_id',
            'provinces' => 'nullable|array|exists:provinces,_id'
        ];
    }
}
