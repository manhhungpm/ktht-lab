<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/25/2019
 * Time: 11:23 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $table = 'user_roles';

    protected $fillable = ['role_id','user_id'];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }
}