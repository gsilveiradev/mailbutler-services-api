<?php

namespace App\Entities\Api\V1;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class User extends Authenticatable implements Transformable
{
    use TransformableTrait;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];
}
