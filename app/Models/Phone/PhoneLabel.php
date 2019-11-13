<?php

namespace App\Models\Phone;

use App\Models\DurationType;
use Illuminate\Database\Eloquent\Model;

class PhoneLabel extends Model
{
    protected $fillable = ['phone_number','who_updated','status'];
}