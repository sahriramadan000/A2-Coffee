<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UpdateTagRequest extends FormRequest
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
        $tagId = $this->route('tagId');
        $request->merge(['status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN)]);

        return [
            'name' => 'required|string|max:30|unique:tags,name,'. $tagId,
            'position' => 'required|integer|min:0',
            'status' => 'required|boolean',
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
