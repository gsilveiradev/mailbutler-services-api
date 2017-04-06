<?php

namespace App\Entities\Api\V1;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Prettus\Repository\Contracts\Presentable;
use Prettus\Repository\Traits\PresentableTrait;

class User extends Authenticatable implements Presentable
{
    use PresentableTrait;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];
}
