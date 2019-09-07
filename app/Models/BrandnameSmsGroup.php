<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSmsGroup extends Model
{
    protected $table = 'brandname_sms_groups';

    protected $fillable = ['name', 'description', 'version', 'active', 'who_created', 'who_updated', 'id', 'when_created', 'when_updated'];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public function brandnameSmsType()
    {
        return $this->hasMany(BrandnameSmsType::class,'group_id');
    }
}