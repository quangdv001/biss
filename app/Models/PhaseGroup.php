<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhaseGroup extends Model
{
    protected $table = 'phase_group';
    public $timestamps = false;
    public $incrementing = true;
    protected $fillable = [
        'phase_id',
        'group_id',
        'qty',
    ];
}
