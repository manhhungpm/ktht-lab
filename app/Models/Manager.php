<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $table = 'managers';

    protected $fillable = ['id', 'name', 'description', 'active', 'who_created', 'who_updated', 'updated_at', 'created_at'];

    public $timestamps = true;

}