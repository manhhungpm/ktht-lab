<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    protected $table = 'faculty';

    protected $fillable = ['id', 'name', 'description', 'status', 'created_at', 'updated_at'];
}