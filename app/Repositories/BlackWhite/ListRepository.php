<?php

namespace App\Repositories\BlackWhite;

use App\Models\AliasBlackWhiteLists;
use App\Repositories\BaseRepository;

class ListRepository extends BaseRepository
{
    public function model()
    {
        return AliasBlackWhiteLists::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'alias', $orderType = 'desc')
    {
        $query = $this->model
            ->where(function ($query) use ($keyword) {
                $query->where('alias', 'LIKE', "%$keyword%");
            });

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'type':
                    if (isset($item)) {
                        $query->whereIn('type', $item);
                    }
                    break;
                case 'provider':
                    if (isset($item)) {
                        $query->whereIn('provider', $item);
                    }
                    break;
                case 'manager':
                    if(isset($item)){
                        $query->whereIn('manager_id',$item);
                    }
                    break;
                case 'who_created':
                    if (isset($item)) {
                        $query->whereIn('who_created', $item);
                    }
                    break;
                case 'who_updated':
                    if (isset($item)) {
                        $query->whereIn('who_updated', $item);
                    }
                    break;
                case 'created_at':
                    if (isset($item)) {
                        $query->where('created_at', '>=', $item[0])->where('created_at', '<=', $item[1]);
                    }
                    break;
                case 'updated_at':
                    if (isset($item)) {
                        $query->where('updated_at', '>=', $item[0])->where('updated_at', '<=', $item[1]);
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
            $query->with('manager')->select('id', 'who_created', 'who_updated', 'active', 'alias', 'type', 'created_at', 'updated_at', 'url', 'description', 'provider', 'manager_id');
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

    public function addAlias($arr)
    {
        $query = new $this->model;
        $arr['who_created'] = \auth()->user()->name;
        $arr['active'] = ACTIVE;
        $query->manager_id = $arr['manager'];
        $query->fill($arr);
        return $query->save();
    }

    public function editAlias($arr)
    {
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $arr['who_updated'] = \auth()->user()->name;
            $query->manager_id = $arr['manager'];
            $query->fill($arr);
            return $query->save();
        }
        return false;
    }

    public function setActive($id)
    {
        $query = $this->model->where('id', $id);

        $result = $query->update(['active' => ACTIVE]);
        return $result;
    }

    public function setDisable($id)
    {
        $query = $this->model->whereIn('id', $id);

        $result = $query->update(['active' => INACTIVE]);
        return $result;
    }
}