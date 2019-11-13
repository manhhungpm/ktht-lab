<?php

namespace App\Models\Statistic;

use App\Models\MsisdnType;
use Illuminate\Database\Eloquent\Model;

class TypeNumberMsisdn extends Model
{
    protected $table = 'type_number_msisdn';

    public function msisdnType()
    {
        return $this->hasOne(MsisdnType::class, 'id', 'msisdn_type_id');
    }

}