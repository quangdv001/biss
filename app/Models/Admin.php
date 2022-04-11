<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'admin';
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'phone',
        'email',
        'mns',
        'birthday',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dates = ['created_at', 'updated_at'];
}
