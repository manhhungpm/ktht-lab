<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSmsType extends Model
{
    protected $table = 'brandname_sms_types';

    protected $fillable = ['name', 'description', 'version', 'active', 'who_created'
        , 'who_updated', 'id', 'when_created', 'when_updated', 'alias', 'prefix', 'priority','group_id'];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public function brandnameSmsGroup(){
        return $this->belongsTo(BrandnameSmsGroup::class,'group_id');
    }
}