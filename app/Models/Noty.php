<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noty extends Model
{
    use HasFactory;

    protected $table = 'noty';
    protected $fillable = [
        'status',
        'type',
        'project_id',
        'group_id',
        'phase_id',
        'admin_id_c',
        'admin_id',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function adminc()
    {
        return $this->belongsTo(Admin::class, 'admin_id_c', 'id');
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
