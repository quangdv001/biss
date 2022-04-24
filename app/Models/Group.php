<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';
    protected $fillable = [
        'name',
        'project_id',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function admin()
    {
        return $this->belongsToMany(Admin::class);
    }

    public function ticket()
    {
        return $this->hasMany(Ticket::class,'group_id','id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }
}
