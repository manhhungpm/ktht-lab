<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/11/2019
 * Time: 11:08 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model{
    protected $fillable = ['name','group_name','description','version','active','who_created','who_updated','id','value'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';
}