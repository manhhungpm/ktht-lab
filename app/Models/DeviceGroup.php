<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 02/11/2019
 * Time: 22:08
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceGroup extends Model
{
    protected $table = 'group_devices';

    protected $fillable = ['id', 'name', 'display_name', 'description', 'status', 'created_at', 'updated_at','provider_id'];

    public function provider(){
        return $this->hasOne(Provider::class, 'id', 'provider_id');
    }
}