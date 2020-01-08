<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    protected $table = 'rent';

    protected $fillable = ['id', 'user_id', 'description', 'status', 'start_date', 'dua_date',
        'created_at', 'updated_at', 'project_id', 'leader_id', 'priority', 'number_of_email'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function deviceType()
    {
        return $this->belongsToMany(DeviceType::class, 'device_rent', 'rent_id',
            'device_type_id')->withPivot('amount');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function leader()
    {
        return $this->hasOne(User::class, 'id', 'leader_id');
    }
}
