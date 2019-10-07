<?php

namespace App\Models\Statistic;

use App\Models\MsisdnType;
use Illuminate\Database\Eloquent\Model;

class TypeNumberOutCall extends Model
{
    protected $table = 'type_number_out_call';

    public function msisdnType()
    {
        return $this->hasOne(MsisdnType::class, 'id', 'msisdn_type_id');
    }

}

