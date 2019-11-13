<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\PermissionTrait;

class Permission extends Model
{

    use PermissionTrait;

    protected $fillable = ['value', 'active'];
    protected $hidden = ['pivot'];
    public $timestamps = false;
}
