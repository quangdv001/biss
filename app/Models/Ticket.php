<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'ticket';
    protected $fillable = [
        'name',
        'description',
        'input',
        'output',
        'status',
        'created_time',
        'deadline_time',
        'complete_time',
        'qty',
        'priority',
        'admin_id_c',
        'project_id',
        'group_id',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsToMany(Admin::class);
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class,'admin_id_c');
    }

    public function group()
    {
        return $this->belongsTo(Group::class,'group_id','id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
