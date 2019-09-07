<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameWrongTime extends  Model{
    protected $table = 'brandname_wrong_time';

    protected $fillable = ['date','active','file_path'];

    public $timestamps = true;

//    public const CREATED_AT = 'when_created';
//
//    public const UPDATED_AT = 'when_updated';
}