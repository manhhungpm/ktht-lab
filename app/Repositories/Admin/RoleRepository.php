<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 6/29/2019
 * Time: 2:52 PM
 */

namespace App\Repositories\Admin;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function model()
    {
        return Role::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = new Role();
        $query = $query
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id','name');
            if ($limit > 0) {
                $query->skip($offset)
                    ->take($limit);
            }

            if ($orderBy != null && $orderType != null) {
                $query->orderBy($orderBy, $orderType);
            }
        } else {
            return $query->count();
        }

        return $query->get();

    }
}