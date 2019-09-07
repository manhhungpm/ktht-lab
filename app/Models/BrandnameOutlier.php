<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameOutlier extends  Model{
    protected $table = 'brandname_outlier';

    protected $fillable = ['date','vip_outlier','pot_outlier','nor_outlier','vip_month_outlier','pot_month_outlier','nor_month_outlier','file_path'];

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';
}