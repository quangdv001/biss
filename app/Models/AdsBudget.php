<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsBudget extends Model
{
    use HasFactory;

    protected $table = 'ads_budget';
    protected $fillable = [
        'campaign_id',
        'amount',
        'note',
        'entered_time',
        'created_by',
        'updated_by',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function campaign()
    {
        return $this->belongsTo(AdsCampaign::class, 'campaign_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'id');
    }

    public function editor()
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'id');
    }
}
