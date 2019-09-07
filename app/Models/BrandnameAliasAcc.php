<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameAliasAcc extends Model
{
    protected $table = 'brandname_alias_acc';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

}