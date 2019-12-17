<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceRent extends Model
{
    protected $table = 'device_rent';

    protected $fillable = ['id', 'device_type_id', 'rent_id', 'amount'];

}