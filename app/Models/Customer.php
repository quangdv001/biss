<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = [
        'status',
        'source',
        'response',
        'start_time',
        'created_time',
        'name',
        'title',
        'company',
        'phone',
        'email',
        'province',
        'description',
        'note',
        'admin_id',
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
