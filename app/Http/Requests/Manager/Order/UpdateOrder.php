<?php

namespace App\Http\Requests\Manager\Order;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'manual'        => 'nullable|boolean',
            'verified'      => 'nullable|boolean',
            'activated_at'  => 'nullable|date',
            'expires_at'    => 'required_with:manual|date',
            'price'         => 'required_with:manual|string',
            'expires_month' => 'nullable|numeric',
            'discount'      => 'nullable|numeric',
            'discount_type' => ['required', Rule::in([Order::DISCOUNT_NORMAL, Order::DISCOUNT_PERCENT])],
        ];
    }

    public function activeAt()
    {
        return $this->activated_at ? Carbon::createFromTimestamp(strtotime($this->activated_at)) : now();
    }

    public function expiresAt()
    {
        return $this->expires_at ? Carbon::createFromTimestamp(strtotime($this->expires_at)) : null;
    }
}
