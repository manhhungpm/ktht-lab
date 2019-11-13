<?php

namespace App\Models\Statistic;

use App\Models\MsisdnType;
use Illuminate\Database\Eloquent\Model;

class TypeDurationType extends Model
{
    protected $table = 'type_duration_type';

    public function msisdnType()
    {
        return $this->hasOne(MsisdnType::class, 'id', 'msisdn_type_id');
    }

    public function callDurationType()
    {
        return $this->hasOne(CallDurationType::class, 'id', 'call_duration_type_id');
    }
}