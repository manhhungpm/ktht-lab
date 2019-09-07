<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameThreshold extends Model{

    protected $table = 'ad_brandname_threshold';

    protected $fillable = ['id','date_threshold','month_threshold','br_type','who_created','active','version','who_updated'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    const UPDATED_AT = 'when_updated';
    const CREATED_AT = 'when_created';
}