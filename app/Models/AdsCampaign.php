<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsCampaign extends Model
{
    use HasFactory;

    const CHANNELS = [
        'facebook' => 'Facebook',
        'tiktok' => 'TikTok',
        'google' => 'Google Ads',
        'zalo' => 'Zalo',
        'other' => 'Khác',
    ];

    protected $table = 'ads_campaign';
    protected $fillable = [
        'name',
        'project_id',
        'channel',
        'handler_id',
        'start_time',
        'end_time',
        'created_by',
        'updated_by',
    ];
    protected $dates = ['created_at', 'updated_at'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function handler()
    {
        return $this->belongsTo(Admin::class, 'handler_id', 'id');
    }

    public function budget()
    {
        return $this->hasMany(AdsBudget::class, 'campaign_id', 'id');
    }

    public function spend()
    {
        return $this->hasMany(AdsSpend::class, 'campaign_id', 'id');
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
