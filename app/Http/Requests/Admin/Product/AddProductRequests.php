<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddProductRequests extends FormRequest
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
            'code'              => 'required|string|unique:products,code',
            'name'              => 'required|string',
            'category'          => 'required|string',
            'description'       => 'nullable|string',
            'cost_price'        => 'required|numeric|min:0',
            'selling_price'     => 'required|numeric|min:0',
            'picture'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_discount'       => 'required',
            'percent_discount'  => 'required|numeric|min:0|max:100',
            'price_discount'    => 'required|numeric|min:0',
            'stock_per_day'     => 'required|numeric|min:0',
            'minimum_stock'     => 'required|numeric|min:0',
            'status'            => 'required',
            'tag_id'            => 'nullable|array',
            'tag_id.*'          => 'exists:tags,id',
            'addon_id'          => 'nullable|array',
            'addon_id.*'        => 'exists:addons,id',
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
