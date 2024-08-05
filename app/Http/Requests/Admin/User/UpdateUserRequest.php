<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('userId');
        return [
            'fullname'      => 'required|string',
            'username'      => 'required|string',
            'old_password'  => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (!$this->filled('new_password') && !Hash::check($value, $this->user()->password)) {
                        $fail('The old password is incorrect.');
                    }
                },
            ],
            'new_password'  => 'nullable|string|min:8|different:old_password',
            'email'         => 'required|email|unique:users,email'. ($userId ? ','.$userId : ''),
            'avatar'        => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone'         => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'address'       => 'nullable|string',
            'role_id'       => 'required|exists:roles,id',
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
