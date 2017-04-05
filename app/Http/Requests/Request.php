<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

abstract class Request extends FormRequest
{
    /**
     * Overwrite Laravel Request method because API is always returning json
     * @return bool
     */
    public function wantsJson()
    {
        return true;
    }

    protected function formatErrors(Validator $validator)
    {
        return [
            'error' => true,
            'message' => $validator->errors()
        ];
    }
}
