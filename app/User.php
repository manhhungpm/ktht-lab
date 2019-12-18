<?php

namespace App;

use App\Models\Classes;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Role;
use App\Models\UserRole;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Traits\UserPermissionTrait;

/**
 * Class User
 * @package App
 *
 * @OA\Schema(
 *     description="User model",
 *     title="User model",
 *     required={"name", "email", "password"},
 *     @OA\Xml(
 *         name="User"
 *     )
 * )
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use UserPermissionTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'display_name', 'mobile_phone', 'expired_at', 'active', 'who_created', 'who_updated',
        'version', 'class_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $dateFormat = 'Y-m-d H:i:s';


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @OA\Property(
     *     format="int32",
     *     description="ID",
     *     title="ID",
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Name",
     *     title="Name",
     *     maximum=255
     * )
     *
     * @var integer
     */
    private $name;

    /**
     * @OA\Property(
     *     format="string",
     *     description="Password",
     *     title="Password",
     *     maximum=255
     * )
     *
     * @var integer
     */
    private $password;

    public function userRole()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function classes()
    {
        return $this->hasOne(Classes::class, 'id', 'class_id');
    }

}
