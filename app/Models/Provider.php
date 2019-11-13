<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';

    protected $fillable = ['id', 'name', 'address', 'description', 'status', 'created_at', 'updated_at'];
}