<?php

namespace App\Repositories\Brandname;

use App\Models\Brandname;
use App\Repositories\BaseRepository;

class ListRepository extends BaseRepository
{

    public function model()
    {
        return Brandname::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->select('id', 'brandname', 'br_type', 'active', 'reason', 'who_created', 'when_created','when_updated');

        collect($search)->each(function ($item, $key) use ($query) {
            switch ($key) {
                case 'brandname':
                    $query->where('brandname', 'LIKE', "%$item%");
                    break;
                case 'status':
                    if (isset($item)) {
                        $query->whereIn('active', $item);
                    }
                    break;
                case 'br_type':
                    if (isset($item)) {
                        $query->whereIn('br_type', $item);
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
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

    public function setActive($ids): bool
    {
        $query = $this->model->whereIn('id', $ids);
        if ($query->update(['active' => 1])) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function setDisable($ids): bool
    {
        $query = $this->model->whereIn('id', $ids);
        if ($query->update(['active' => 0])) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;

    }

    public function addBrandname($arr)
    {
        $query = $this->model;
        $arr['who_created'] = \auth()->user()->name;
        $arr['active'] = '1';
        $arr['version'] = '1';
        $query->fill($arr);
        return $query->save();
    }

    public function editBrandname($arr)
    {
        $query = $this->model->find($arr['id']);
        if ($query != null) {
            $arr['who_updated'] = \auth()->user()->name;
            $query->fill($arr);
            return $query->save();
        }
        return false;
    }
}