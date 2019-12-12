<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;

class ActionsLog extends Model
{
    protected $fillable = ['user_id', 'username', 'action_name', 'created_at', 'class_name', 'old_value', 'new_value', 'object_name', 'object_id','ip'];
    protected $table = 'actions_logs';

    public $timestamps = false;

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}