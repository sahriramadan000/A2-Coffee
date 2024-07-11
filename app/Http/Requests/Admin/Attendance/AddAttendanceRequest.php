<?php

namespace App\Http\Requests\Admin\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddAttendanceRequest extends FormRequest
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
            'user_id'   => 'required|exists:users,id',
            'date'      => 'required|date',
            'check_in'  => 'required|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'status'    => 'nullable|in:on_time,late,absent',
        ];
    }

    protected function failedValidation($validator)
    {
        $errors = $validator->errors()->all();
        $errorMessage = implode(' ', $errors);

        // Menyimpan pesan kesalahan dalam session flash
        $this->session()->flash('failed', 'Failed Insert: ' . $errorMessage);

        parent::failedValidation($validator);
    }
}
