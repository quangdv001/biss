<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'project';
    protected $fillable = [
        'name',
        'description',
        'project_id',
        'type'
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsToMany(Admin::class);
    }

    public function planer()
    {
        return $this->belongsTo(Admin::class,'planer_id','id');
    }

    public function executive()
    {
        return $this->belongsTo(Admin::class,'executive_id','id');
    }

    public function group()
    {
        return $this->hasMany(Group::class,'project_id','id')->orderBy('id', 'DESC');
    }

    public function phase()
    {
        return $this->hasMany(Phase::class, 'project_id', 'id');
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'project_id', 'id');
    }
}
