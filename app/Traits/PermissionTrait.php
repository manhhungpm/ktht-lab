<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait PermissionTrait
{
    public function save(array $options = [])
    {   //both inserts and updates
        $result = parent::save($options);
        Cache::tags(Config::get('permission.role_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    public function delete(array $options = [])
    {   //soft or hard
        $result = parent::delete($options);
        Cache::tags(Config::get('permission.role_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    public function restore()
    {   //soft delete undo's
        $result = parent::restore();
        Cache::tags(Config::get('permission.role_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    /**
     * setting many to many relations with Role
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany(
            Config::get('permission.role'),
            Config::get('permission.role_permission_table'),
            Config::get('permission.permission_foreign_key'),
            Config::get('permission.role_foreign_key')
        );
    }

    /**
     * setting many to many relations with User
     * @return mixed
     */
    public function users()
    {
        return $this->belongsToMany(
            Config::get('permission.user'),
            Config::get('permission.user_permission_table'),
            Config::get('permission.permission_foreign_key'),
            Config::get('permission.user_foreign_key')
        );
    }
}