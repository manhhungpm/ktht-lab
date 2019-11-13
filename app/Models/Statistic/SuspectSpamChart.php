<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;

class SuspectSpamChart extends Model
{
    protected $table = 'suspect_spam_chart';

//    protected $fillable = ['id', 'name', 'description', 'active', 'who_created', 'who_updated', 'updated_at', 'created_at'];

    public $timestamps = false;

}