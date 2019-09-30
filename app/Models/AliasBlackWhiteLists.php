<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AliasBlackWhiteLists extends Model
{
    protected $fillable = ['id', 'who_created', 'who_updated', 'active', 'alias', 'type', 'created_at', 'updated_at', 'url', 'description', 'provider', 'manager_id'];

    protected $table = 'alias_black_white_lists';

    public $timestamps = true;

    public function manager()
    {
        return $this->hasOne(Manager::class,'id','manager_id')->select('id','name');
    }

}