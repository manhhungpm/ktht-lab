<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/10/2019
 * Time: 8:37 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAccount extends Model
{
    protected $fillable = ['name','version','active','who_created','who_updated','id','secret_key'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';

    public function apiAccountApis(){
        return $this->hasMany(ApiAccountApi::class,'account_id');
    }

    public function api(){
        return $this->belongsToMany(Api::class,ApiAccountApi::class,'account_id');
    }
}
