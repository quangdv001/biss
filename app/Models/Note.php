<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'note';
    protected $fillable = [
        'note',
        'admin_id',
        'group_id',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }
}
