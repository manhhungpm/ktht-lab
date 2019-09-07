<?php

return [
    'cache_ttl' => 60 * 24,
    'user' => 'App\User',
    'role' => 'App\Models\Role',
    'roles_table' => 'roles',
    'permission' => 'App\Models\Permission',
    'permissions_table' => 'permissions',
    'role_permission_table' => 'permission_role',
    'user_permission_table' => 'user_permissions',
    'user_role_table' => 'user_roles',
    'user_foreign_key' => 'user_id',
    'role_foreign_key' => 'role_id',
    'permission_foreign_key' => 'permission_id'
];
