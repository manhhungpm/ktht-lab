<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/12/2019
 * Time: 1:44 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForceStaffEvent extends Model{

    protected $table = 'forcestaff_events';

    protected $fillable = ['event','event_time','forcestaff_id','when_created','id'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';
}