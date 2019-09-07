<?php

namespace App\Repositories;

use App\Soap\Request\Authen;
use App\Soap\Response\AuthenResponse;
use App\User;
use Artisaninweb\SoapWrapper\SoapWrapper;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use vendor\project\StatusTest;

class UserRepository extends BaseRepository
{
    /**
     * @var SoapWrapper
     */
    protected $soapWrapper;

    public function __construct(SoapWrapper $soapWrapper)
    {
        $this->soapWrapper = $soapWrapper;
    }

    /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function authenticateWithSSO($username, $password)
    {
        $user = User::where('name', $username)->first();
        if ($user) {
            $this->soapWrapper->add('passportWSPortBinding', function ($service) {
                $service
                    ->wsdl(SSO_WSDL)
                    ->trace(true)
                    ->classmap([
                        Authen::class,
                        AuthenResponse::class
                    ]);
            });

            $response = $this->soapWrapper->call('passportWSPortBinding.authen', [
                new Authen($username, $password, DOMAIN_CODE)
            ]);

            if ($response->return && $response->return->errorCode && $response->return->errorCode->code == 0) {
                $description = $response->return->errorCode->description;
                $result = simplexml_load_string($description);

                if ($result === false) {
                    return false;
                } else {
                    $userData = $result->UserData->Row;

                    $user->last_login_at = Carbon::now();
                    $user->email = $userData->EMAIL;
                    $user->mobile_phone = $userData->CELLPHONE;
                    $user->display_name = $userData->FULL_NAME;
                    $user->save();

                    $token = auth()->login($user);

                    return $token;
                }
            }
        }

        return false;
    }

    public function authen($username){
        $user = User::where('name', $username)->first();
        if ($user) {
            $token = auth()->login($user);
            return $token;
        }
    }

    /**
     * @param null $keyword
     * @param bool $counting
     * @param int $limit
     * @param int $offset
     * @param string $orderBy
     * @param string $orderType
     * @return mixed
     */
    public function getList($keyword = null, $search = [], $counting = false, $limit = 10, $offset = 0, $orderBy = 'when_updated', $orderType = 'desc')
    {

        $query = User::select('id', 'name', 'display_name', 'active', 'expired_at', 'who_updated', 'when_updated', 'expired_at', 'email', 'mobile_phone');

        //OK
        if (array_key_exists('status', $search) && ($search['status'] != "")) {
            $query = $query->whereIn('active', $search['status']);
        }

        if (array_key_exists('name', $search) && ($search['name'] != "")) {
            $name = $search['name'];
            $query->where('name', 'LIKE', "%$name%")
                ->orWhere('display_name', 'LIKE', "%$name%");
        }

        if (array_key_exists('role',$search) && ($search['role'] != null)) {
            $query = $query->whereHas('userRole', function ($q) use ($search) {
                $q->whereIn('role_id', $search['role']);
            });
        }


        if (!$counting) {
            $query->with("userRole.role");

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
        $query = new User();

        $query = $query->where('id', $id)->update(['active' => 0]);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function setActive($id)
    {
        $query = new User();

        $query = $query->where('id', $id)->update(['active' => 1]);

        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function addUser($arr)
    {
//        dd($arr);
        $role = $arr['role'];
        $user = new User();
        $arr['who_created'] = \auth()->user()->name;
        $arr['active'] = '1';
        $user->fill($arr);
        if ($user->save()) {
            if ($role != null) {
                $user->roles()->attach($role);
            }
            return true;
        }
        return false;

    }

    public function editUser($arr)
    {
        $role = $arr['role'];
        $user = User::find($arr['id']);
        if ($user != null) {
            $arr['who_updated'] = \auth()->user()->name;
            $user->fill($arr);
            if ($user->save()) {
                $user->roles()->detach();
                if ($role != null) {
                    $user->roles()->attach($role);
                }
                return true;
            }
            return false;
        }
        return false;
    }


    /**
     * @param $arr
     * @param $ip
     * @return mixed
     */
    public function edit($arr, $ip)
    {
        $role = $arr['role'];
        $user = $this->model->find($arr['id']);
        if ($user != null) {
            $user->fill($arr);
            if ($user->save()) {
                $user->roles()->detach();
                if ($role != null) {
                    $user->attachRole($role['id']);
                }
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * @param $arr
     * @param $ip
     * @return bool
     */
    public function store($arr, $ip)
    {
        $role = $arr['role'];
        $user = new $this->model;
        $user->fill($arr);
        $user->password = Hash::make($arr['password']);
        if ($user->save()) {
            if ($role != null) {
                $user->attachRole($role['id']);
            }
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteById($id, $ip): bool
    {

        $user = $this->model->find($id);

        if ($user != null) {
            $user->roles()->detach();
            if ($this->model->where('id', $id)->delete()) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public function updatePassword($arr)
    {
        $user = $this->model->find($arr['id']);
        if ($user != null) {
            $user->password = Hash::make($arr['password']);
            return $user->save();
        }
        return false;
    }

    public function listingAll($isCounting = false, $keyword = null, $limit = 10, $offset = 0)
    {
        $query = User::where('name', 'LIKE', "%$keyword%")
            ->orWhere('display_name', 'LIKE', "%$keyword%");

        if (!$isCounting) {
            $query->select('id', 'name', 'display_name')
                ->skip($offset)
                ->take($limit)
                ->orderBy('name', 'asc');
        } else {
            return $query->count();
        }

        return $query->get();
    }
}