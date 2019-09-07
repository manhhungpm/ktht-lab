<?php

namespace App\Repositories\Brandname;

use App\Models\BrandnameDetailRequest;
use App\Repositories\BaseRepository;


class BrandnameDetailRequestRepository extends BaseRepository
{
    public function model()
    {
        return BrandnameDetailRequest::class;
    }

    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'created_at', $orderType = 'desc')
    {
        $query = $this->model->with('user');

        //Filter
        collect($search)->each(function ($item, $key) use ($query) {
            $columns = BrandnameDetailRequest::all('params', 'id')->toArray();
            switch ($key) {
                case 'from':
                    if (isset($item)) {
                        $id = [];
                        for ($i = 0; $i < sizeof($columns); $i++) {
                            $paramArr = ($columns[$i]['params']);
                            $paramId = ($columns[$i]['id']);
                            $date = date(json_decode($paramArr)->from);
                            if (strtotime($date) >= strtotime($item[0]) && strtotime($date) <= strtotime($item[1])) {
                                array_push($id, $paramId);
                            }
                        }
                        $query->whereIn('id', $id);
                    }
                    break;
                case 'to':
                    if (isset($item)) {
                        $id = [];
                        for ($i = 0; $i < sizeof($columns); $i++) {
                            $paramArr = ($columns[$i]['params']);
                            $paramId = ($columns[$i]['id']);
                            $date = date(json_decode($paramArr)->to);
                            if (strtotime($date) >= strtotime($item[0]) && strtotime($date) <= strtotime($item[1])) {
                                array_push($id, $paramId);
                            }
                        }
                        $query->whereIn('id', $id);
                    }
                    break;
                case 'status':
                    if (isset($item)) {
                        $query->whereIn('status', $item);
                    }
                    break;
                case 'created_at':
                    if (isset($item)) {
//                        $query->whereDate('created_at', '=', $item);
                        $query->whereDate('created_at', '>=', $item[0]);
                        $query->whereDate('created_at', '<=', $item[1]);
                    }
                    break;
                default:
                    break;
            }
        });

        if (!$counting) {
            $query->select('id', 'user_id', 'status', 'file_path', 'params', 'created_at');
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

    public function addDetailRequest($arr)
    {
        $query = $this->model;

        $phone = explode(";", $arr['phone']);
        $from = $arr['time'][0];
        $to = $arr['time'][1];
        $obj = array(
            'phone' => $phone,
            'from' => $from,
            'to' => $to
        );
        $params = json_encode($obj);

        $pattern = '/^9(\d){8}/';
        $bool = false;
        for ($i = 0; $i < sizeof($phone); $i++) {
            if (preg_match($pattern, $phone[$i])) {
                $bool = true;
            } else return false;
        }

        if ($bool) {
            $arr['user_id'] = \auth()->user()->id;
            $arr['status'] = 0;
            $arr['params'] = $params;
            $query->fill($arr);
        }
        return $query->save();
    }

    public function resendDetailRequest($arr)
    {
        $query = $this->model;

        $arr['user_id'] = \auth()->user()->id;
        $arr['status'] = 0;

        $query->fill($arr);
        return $query->save();

    }

    public function downloadDetailRequest($arr)
    {
    }
}