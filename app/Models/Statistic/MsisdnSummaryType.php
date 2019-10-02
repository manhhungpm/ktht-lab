<?php

namespace App\Models\Statistic;

use App\Models\DurationType;
use Illuminate\Database\Eloquent\Model;

class MsisdnSummaryType extends Model
{
    protected $table = 'msisdn_summary_type';

    public function durationType()
    {
        return $this->hasOne(DurationType::class,'id','duration_type_id');
    }

}