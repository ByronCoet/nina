<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'company_id',
        'campaign_start',
        'campaign_end'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
