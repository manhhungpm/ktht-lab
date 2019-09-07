<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/11/2019
 * Time: 11:13 AM
 */

namespace App\Repositories\Admin;

use App\Models\Config;
use App\Repositories\BaseRepository;

class ConfigRepository extends BaseRepository
{
    public function model()
    {
        return Config::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->select('id', 'name', 'group_name', 'description', 'active', 'who_updated', 'when_updated', 'value');
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

    public function addConfig($arr)
    {
        $query = $this->model;
        $arr['active'] = '1';
        $arr['version'] = '1';
        $arr['who_created'] = \auth()->user()->name;
        $query->fill($arr);
        return $query->save();
    }

    public function editConfig($arr)
    {
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $arr['who_updated'] = \auth()->user()->name;
            $query->fill($arr);
            if ($query->save()) {
                return true;
            }
            return false;
        }
        return false;
    }
}