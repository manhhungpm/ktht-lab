<?php

namespace App\Models\Statistic;

use App\Models\MsisdnType;
use Illuminate\Database\Eloquent\Model;

class CallDurationType extends Model
{
    protected $table = 'call_duration_type';

    protected $fillable = ['id','label'];
}
