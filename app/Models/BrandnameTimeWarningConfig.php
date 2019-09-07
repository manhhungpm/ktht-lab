<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameTimeWarningConfig extends Model
{
    protected $table = 'brandname_time_warning_config';
    protected $fillable = ['type_id', 'apply_from', 'apply_to', 'week_day', 'time_frame', 'high_warning_from', 'high_warning_to', 'danger_warning_from'
        , 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'active'];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public function brandnameSmsType()
    {
        return $this->belongsTo(BrandnameSmsType::class, 'type_id')->select('id', 'name', 'group_id');
    }

}