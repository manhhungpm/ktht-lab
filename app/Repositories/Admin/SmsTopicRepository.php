<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/12/2019
 * Time: 8:27 AM
 */

namespace App\Repositories\Admin;

use App\Models\ForceStaffInfo;
use App\Repositories\BaseRepository;

class SmsTopicRepository extends BaseRepository
{
    public function model()
    {
        return ForceStaffInfo::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'name', $orderType = 'asc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->with('forceStaffEvent')->select('id','name','ip','master_id','slave_id','topic');
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