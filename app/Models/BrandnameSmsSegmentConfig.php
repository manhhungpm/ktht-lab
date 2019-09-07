<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSmsSegmentConfig extends Model
{
    protected $table = 'brandname_sms_segment_configs';

    protected $fillable = ['name', 'per_day', 'per_month', 'type_id', 'apply_from'
        , 'apply_to', 'who_created', 'who_updated', 'ip', 'active'];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public function brandnameSmsType()
    {
        return $this->belongsTo(BrandnameSmsType::class, 'type_id')->select('id', 'name', 'group_id');
    }

}