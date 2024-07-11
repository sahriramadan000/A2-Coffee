<?php

namespace App\Http\Requests\Admin\Coupon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'              => 'required|string',
            'code'              => 'required|unique:coupons,code',
            'type'              => 'required|string',
            'discount_value'    => 'required|numeric',
            'minimum_cart'      => 'nullable|numeric',
            'discount_threshold'=> 'nullable|numeric|min:0',
            'max_discount_value'=> 'nullable|numeric|min:0',
            'expired_at'        => 'required|date',
            'limit_usage'       => 'required|numeric|min:0',
            'status'            => 'required',
        ];
    }

    protected function failedValidation($validator)
    {
        $errors = $validator->errors()->all();
        $errorMessage = implode(' ', $errors);

        // Menyimpan pesan kesalahan dalam session flash
        $this->session()->flash('failed', 'Failed Insert: '.$errorMessage);

        parent::failedValidation($validator);
    }
}
