<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $table = 'employees';
    protected $fillable = [
        'user_id',
        'name',
        'email', 
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
