<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/9/2019
 * Time: 4:31 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Api extends Model
{
    protected $fillable = ['name','display_name','description','version','active','who_created','who_updated','id'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';
}