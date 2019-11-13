<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:12
 */

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['id', 'name', 'description', 'status', 'created_at', 'updated_at'];

    public function user()
    {
        return $this->belongsToMany(User::class,'project_user','project_id','user_id');
    }

    public function deviceType()
    {
        return $this->belongsToMany(DeviceType::class, 'project_device',
            'project_id','device_id');
    }
}