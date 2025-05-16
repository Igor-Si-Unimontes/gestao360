<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticable;

class User extends Authenticable
{
    // Serve para usar o SoftDeletes
    use SoftDeletes;

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


    // atributos que serão escondidos ao retornar o objeto
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
