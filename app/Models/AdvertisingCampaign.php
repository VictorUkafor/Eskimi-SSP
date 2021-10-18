<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisingCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'uid',
		'date_from',
		'date_to',
		'daily_budget',
		'total_budget',
        'creative_upload',
    ];

    protected $casts = [
        'creative_upload' => 'array',
    ];
}
