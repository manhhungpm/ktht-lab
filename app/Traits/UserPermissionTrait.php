<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait UserPermissionTrait
{
    /**
     *  cache roles
     * @return mixed
     */
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'roles_for_user_' . $this->$userPrimaryKey;

        $collectionRole = Cache::tags(Config::get('permission.user_role_table'))->remember($cacheKey, Config::get('permission.cache_ttl'), function () {
            return $this->roles()->where('active',ACTIVE)->get();
        });

//        $collectionRole = collect($collectionRole)->filter(function ($value) {
//            return $value->active == 1;
//        });

        return $collectionRole;
    }

    /**
     *  cache permissions
     * @return mixed
     */
    public function cachedPermissions()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'permissions_for_user_' . $this->$userPrimaryKey;
        $collectionPermission = Cache::tags(Config::get('permission.user_permission_table'))->remember($cacheKey, Config::get('permission.cache_ttl'), function () {
            return $this->permissions()->where('active',ACTIVE)->get();
        });

        return $collectionPermission;
    }

    /**
     * setting many to many relations with Role
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(
            Config::get('permission.role'),
            Config::get('permission.user_role_table'),
            Config::get('permission.user_foreign_key'),
            Config::get('permission.role_foreign_key')
        );
    }

    /**
     * setting many to many relations with Permission
     * @return mixed
     */
    public function permissions()
    {
        return $this->belongsToMany(
            Config::get('permission.permission'),
            Config::get('permission.user_permission_table'),
            Config::get('permission.user_foreign_key'),
            Config::get('permission.permission_foreign_key')
        );
    }

    public function save(array $options = [])
    {   //both inserts and updates
        $result = parent::save($options);
        Cache::tags(Config::get('permission.user_role_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    public function delete(array $options = [])
    {   //soft or hard
        $result = parent::delete($options);
        Cache::tags(Config::get('permission.user_role_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    public function restore()
    {   //soft delete undo's
        $result = parent::restore();
        Cache::tags(Config::get('permission.user_role_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $names Role name or array of role names.
     * @param bool $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($names, $requireAll = false)
    {
        $roles = $this->cachedRoles();

        $roles = collect($roles)->map(function ($item) {
            return $item->name;
        })->all();

        if (is_array($names)) {
            foreach ($names as $roleName) {
                $hasRole = in_array($roleName, $roles);

                if ($hasRole && !$requireAll) {
                    return true;
                } else if (!$hasRole && $requireAll) {
                    return false;
                }
            }
        } else {
            return in_array($names, $roles);
        }

        return $requireAll;
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param string|array $name Permission string or array of permissions.
     * @param bool $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function can($name, $requireAll = false)
    {
        $roles = $this->cachedRoles();
        $permissionCaches = $this->cachedPermissions();

        $permissions = array();
        foreach ($roles as $role) {
            $permissions = collect($permissions)->merge($role->cachedPermissions())->all();
        }

        $permissions = collect($permissions)->merge($permissionCaches)->all();

        $permissions = collect($permissions)->map(function ($item) {
            return $item->value;
        })->all();

        if (is_array($name)) {
            foreach ($name as $permissionName) {
                $hasPermission = in_array($permissionName, $permissions);

                if ($hasPermission && !$requireAll) {
                    return true;
                } else if (!$hasPermission && $requireAll) {
                    return false;
                }
            }
        } else {
            return in_array($name, $permissions);
        }

        return $requireAll;
    }
}