<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';

    protected $fillable = ['id', 'name', 'description', 'status', 'faculty_id', 'created_at', 'updated_at'];

    public function faculty()
    {
        return $this->hasOne(Faculty::class, 'id', 'faculty_id');
    }
}