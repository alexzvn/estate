<?php

namespace App\Http\Requests\Manager\Order;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user() && $this->user()->can('manager.order.modify');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'activated_at'  => 'nullable|date',
            'expires_at'    => 'nullable|date',
            'expires_month' => 'nullable|numeric',
            'discount'      => 'nullable|numeric',
            'discount_type' => ['required', Rule::in([Order::DISCOUNT_NORMAL, Order::DISCOUNT_PERCENT])],
        ];
    }
}
