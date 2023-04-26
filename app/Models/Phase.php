<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phase extends Model
{
    use HasFactory;

    protected $table = 'phase';
    protected $fillable = [
        'name',
        'start_time',
        'end_time',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function ticket()
    {
        return $this->hasMany(Ticket::class, 'phase_id', 'id');
    }

    public function group()
    {
        return $this->belongsToMany(Group::class, 'phase_group');
    }

    public function phaseGroup()
    {
        return $this->hasMany(PhaseGroup::class, 'phase_id', 'id');
    }
}
