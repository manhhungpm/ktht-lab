<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSegment extends  Model{
    protected $table = 'brandname_segment';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';
}