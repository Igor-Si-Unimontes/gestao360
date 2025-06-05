<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticable;

class User extends Authenticable
{

    protected $fillable = [
        'first_name',
        'last_name',
        'role_id',
        'email',
        'password',
        'token',
        'email_verified_at',
        'last_login_at',
        'blocked_until',
        'active'
    ];


    protected $hidden = [
        'password',
        'token'
    ];

    public function employees()
    {
        return $this->hasOne(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
