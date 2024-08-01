<?php

namespace App\Http\Requests\Admin\Table;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddTableRequest extends FormRequest
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
            'name'              => 'nullable|string',
            'code'              => 'nullable|unique:tables,code',
            'status'            => 'nullable|string',
            'status_position'   => 'nullable|string',
            'barcode'           => 'nullable|string'
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
