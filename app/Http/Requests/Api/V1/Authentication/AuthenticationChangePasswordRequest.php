<?php

namespace App\Http\Requests\Api\V1\Authentication;

use App\Http\Requests\Request;

class AuthenticationChangePasswordRequest extends Request
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
            'current_password' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];
    }
}
