<?php

namespace App\Http\Requests\Manager\Plan;

use App\Enums\PostType;
use App\Repository\Plan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePlan extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.plan.modify');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $plan = $this->getPlan();

        return [
            'name' => 'required|string|unique:plans,name,'. $plan->name .',name',
            'price' => 'nullable|string|regex:/^[0-9,.]+$/',
            'post_type' => ['nullable', 'array', Rule::in(PostType::getValues())],
            'categories' => 'nullable|array|exists:categories,id',
            'provinces' => 'nullable|array|exists:provinces,id'
        ];
    }

    public function getPlan()
    {
        return $this->plan ?? $this->plan = Plan::findOrFail($this->route()->parameters['id'] ?? '');
    }
}
