<?php

namespace App\Http\Requests\Admin\Addon;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddAddonRequest extends FormRequest
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
    public function rules(Request $request)
    {
        // set value
        $parentId = $request->input('parent_id');
        $isPrice = $parentId !== null ? true : false;
        // Merge
        $request->merge(['status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN)]);

        return [
            'name'              => 'required|string|max:30|unique:addons,name',
            'position'          => 'required|integer|min:0',
            'status'            => 'required|boolean',
            'parent_id'         => 'nullable',
            'choose'            => 'nullable',
            'status_optional'   => 'nullable',
            'is_price'          => 'nullable|boolean',
            'price'             => $isPrice ? 'required' : 'nullable',
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
