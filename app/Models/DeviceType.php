<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 05/11/2019
 * Time: 22:48
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    protected $table = 'devices';

    protected $fillable = ['id', 'name', 'display_name', 'description', 'status', 'created_at', 'updated_at',
        'store_id', 'device_group_id', 'amount', 'file', 'total'];

    public function store()
    {
        return $this->hasOne(Store::class, 'id', 'store_id');
    }

    public function deviceGroup()
    {
        return $this->hasOne(DeviceGroup::class, 'id', 'device_group_id');
    }

}
