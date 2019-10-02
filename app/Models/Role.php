<?php

namespace App\Models;

use App\Traits\RoleTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use RoleTrait;

    protected $fillable = ['name', 'active', 'id', 'created_at', 'updated_at', 'description'];

    public $timestamps = true;

    //CONST
    public const A2P = 'a2p';
    public const CC = 'cc';
    public const ROOT = 'root';
    public const ADMIN = 'admin';
    public const USER = 'user';
    public const CSP = 'csp';
    public const POLITIC = 'politic';
    public const SMS2WAY = 'sms2way';
    public const ROAMING = 'roaming';

    public function userRole()
    {
        return $this->hasMany(UserRole::class, 'role_id');
    }


}
