<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenericStatistic extends Model
{
    protected $table = 'generic_statistic';

    public $timestamps = false;

    public const TYPE_SPAM_BLOCK = 'SPAM_BLOCK';

}