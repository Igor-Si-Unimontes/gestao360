<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticable
{
    use HasRoles;

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

    public function getNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    public function employees()
    {
        return $this->hasOne(Employee::class);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pontos()
    {
        return $this->hasMany(Ponto::class, 'usuario_id');
    }
}
