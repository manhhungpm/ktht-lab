<?php
/**
 * Created by PhpStorm.
 * User: hungnm
 * Date: 7/10/2019
 * Time: 8:41 AM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiAccountApi extends Model
{
    protected $fillable = ['account_id', 'api_id'];

    public function apiAccount(){
        return $this->belongsTo(Api::class,'api_id');
    }
}
