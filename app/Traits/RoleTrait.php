<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait RoleTrait
{
    /**
     *  cache permissions
     * @return mixed
     */
    public function cachedPermissions()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'permissions_for_role_' . $this->$userPrimaryKey;
        return Cache::tags(Config::get('permission.role_permission_table'))->remember($cacheKey, Config::get('permission.cache_ttl'), function () {
            return $this->permissions()->get();
        });
    }

    public function save(array $options = [])
    {   //both inserts and updates
        $result = parent::save($options);
        Cache::tags(Config::get('permission.role_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_role_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    public function delete(array $options = [])
    {   //soft or hard
        $result = parent::delete($options);
        Cache::tags(Config::get('permission.role_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_role_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    public function restore()
    {   //soft delete undo's
        $result = parent::restore();
        Cache::tags(Config::get('permission.role_permission_table'))->flush();
        Cache::tags(Config::get('permission.user_role_table'))->flush();
        Cache::tags(Config::get('permission.user_permission_table'))->flush();
        return $result;
    }

    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            Config::get('permission.user'),
            Config::get('permission.user_role_table'),
            Config::get('permission.role_foreign_key'),
            Config::get('permission.user_foreign_key')
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
            Config::get('permission.role_permission_table'),
            Config::get('permission.role_foreign_key'),
            Config::get('permission.permission_foreign_key')
        );
    }
}