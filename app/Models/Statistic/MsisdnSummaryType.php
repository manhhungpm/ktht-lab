<?php

namespace App\Models\Statistic;

use App\Models\DurationType;
use App\Models\Phone\PhoneLabel;
use Illuminate\Database\Eloquent\Model;

class MsisdnSummaryType extends Model
{
    protected $table = 'msisdn_summary_type';
    protected $fillable = ['msisdn',
        'duration_type_id',
        'carrier',
        'num_call_out',
        'sum_duration_call_out',
        'num_call_label_spam',
        'num_call_label_not_spam',
        'num_call_not_label'];

    public $timestamps = false;

    public function durationType()
    {
        return $this->hasOne(DurationType::class, 'id', 'duration_type_id');
    }

    public function label()
    {
        return $this->hasOne(PhoneLabel::class, 'phone_number', 'msisdn');
    }
}