<?php

namespace Modules\Intial\Entities;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'name',
        'email'
    ];
}
