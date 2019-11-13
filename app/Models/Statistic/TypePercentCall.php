<?php

namespace App\Models\Statistic;

use App\Models\MsisdnType;
use Illuminate\Database\Eloquent\Model;

class TypePercentCall extends Model
{
    protected $table = 'type_percent_call';

    public function msisdnType()
    {
        return $this->hasOne(MsisdnType::class, 'id', 'msisdn_type_id');
    }

}