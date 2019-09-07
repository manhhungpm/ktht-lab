<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/10/2019
 * Time: 8:50 AM
 */

namespace App\Repositories\Admin;

use App\Models\ApiAccount;
use App\Repositories\BaseRepository;
use App\User;

class ApiAccountRepository extends BaseRepository
{
    public function model()
    {
        return ApiAccount::class;
    }

    public function getList($keyword = null, $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {
        $query = $this->model
            ->where('name', 'LIKE', "%$keyword%");

        if (!$counting) {
            $query->with('apiAccountApis.apiAccount')->select('id', 'name', 'secret_key', 'active', 'who_updated', 'when_updated');
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

    public function setDisable($id)
    {
        $query = $this->model->where('id', $id)->update(['active' => 0]);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function setActive($id)
    {
        $query = $this->model->where('id', $id)->update(['active' => 1]);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function addAccountApi($arr)
    {
        $api = $arr['api'];
        $arr['active'] = '1';
        $arr['version'] = '1';
        $arr['secret_key'] = $this->generateRandomString();
        $arr['who_created'] = \auth()->user()->name;

        $accountApi = new ApiAccount();
        $accountApi->fill($arr);
        if ($accountApi->save()) {
            if ($api != null) {
                $accountApi->api()->attach($api);
            }
            return true;
        }
        return false;
    }

    public function editAccountApi($arr)
    {
        $api = $arr['api'];
        $arr['who_updated'] = \auth()->user()->name;

        $accountApi = $this->model->find($arr['id']);

        if ($accountApi != null) {
            $accountApi->fill($arr);
            if ($accountApi->save()) {
                $accountApi->api()->detach();
                if ($api != null) {
                    $accountApi->api()->attach($api);
                }
                return true;
            }
            return false;
        }
        return false;
    }

    public function resetPrivateKey($id)
    {
        $key = $this->generateRandomString();
        $accountApi = $this->model->where('id', $id)
            ->update(['secret_key' => $key]);

        if ($accountApi) {
            return true;
        } else {
            return false;
        }

    }

    private function generateRandomString($length = 15)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!"#$%&\'()*+,-./:;<=>?@[\]^_`{|}~';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}