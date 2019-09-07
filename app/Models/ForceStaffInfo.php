<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/12/2019
 * Time: 8:31 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForceStaffInfo extends Model{
    protected $table = 'forcestaff_info';

    protected $fillable = ['name','master_id','slave_id','ip','id','topic'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';

    public function forceStaffEvent(){
        return $this->hasMany(ForceStaffEvent::class,'forcestaff_id');
    }
}