<?php

namespace App\Validators\Api\V1;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

class UserValidator extends LaravelValidator
{
    protected $id;

    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required'
        ],
        ValidatorInterface::RULE_UPDATE => [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,id',
            'password' => 'required'
        ],
    ];
}
