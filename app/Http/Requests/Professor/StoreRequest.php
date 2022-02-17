<?php

namespace App\Http\Requests\Professor;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'max:100',],
            'email' => ['required', 'max:50', 'email', 'unique:users,email'],
            'public_email' => ['max:50', 'email', 'unique:professors,public_email'],
            'password' => ['required','confirmed'],
        ];
    }
}
