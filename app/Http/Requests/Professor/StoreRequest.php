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
            'rede_social1' => ['max:150' ],
            'link_rsocial1' => ['max:150'],
            'rede_social2' => ['max:150' ],
            'link_rsocial2' => ['max:150'],
            'rede_social3' => ['max:150' ],
            'link_rsocial3' => ['max:150'],
            'rede_social4' => ['max:150' ],
            'link_rsocial4' => ['max:150'],
            'password' => ['required','confirmed'],
        ];
    }
}
