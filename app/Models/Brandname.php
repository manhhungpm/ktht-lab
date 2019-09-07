<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brandname extends Model{

    protected $table = 'ad_brandname';

    protected $fillable = ['id','who_created','who_updated','active','brandname','br_type','reason','version','when_created'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';

}