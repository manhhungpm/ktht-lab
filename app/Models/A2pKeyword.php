<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class A2pKeyword extends Model
{
    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public const ACTIVE = 1;

}