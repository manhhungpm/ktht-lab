<?php

namespace App\Models;

use App\Traits\RoleTrait;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use RoleTrait;

    protected $fillable = ['name', 'active', 'id', 'description'];

    public $timestamps = false;

    //CONST
    public const ROOT = 'root';
    public const ADMIN = 'admin';
    public const USER = 'user';

    //new
    public const LEADER = 'leader';
    public const STOCKER = 'stocker';

//    public function userRole()
//    {
//        return $this->hasMany(UserRole::class, 'role_id');
//    }


}
