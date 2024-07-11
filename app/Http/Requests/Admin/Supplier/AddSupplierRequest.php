<?php

namespace App\Http\Requests\Admin\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddSupplierRequest extends FormRequest
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
            'code'      => 'required|unique:suppliers,code',
            'fullname'  => 'required|string',
            'company'   => 'required|string',
            'address'   => 'nullable|string',
            'email'     => 'nullable|email',
            'phone'     => 'nullable|string|regex:/^[0-9]{10,15}$/',
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
