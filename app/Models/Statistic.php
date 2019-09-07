<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statistic  extends Model
{

    public $timestamps = false;

    public const TYPE_SPAM_BLOCK = 'SPAM_BLOCK';

}