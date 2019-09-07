<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BrandnameDetailRequest extends Model
{
    public $timestamps = true;

    protected $table = 'brandname_detail_requests';

    protected $fillable = ['id','user_id','status','file_path','params'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}