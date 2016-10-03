<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
}
