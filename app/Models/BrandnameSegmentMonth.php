<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSegmentMonth extends Model
{
    protected $table = 'brandname_segment_month';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

}