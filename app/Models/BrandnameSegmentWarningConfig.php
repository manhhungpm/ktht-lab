<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSegmentWarningConfig extends Model
{
    protected $table = 'brandname_segment_warning_config';

    protected $fillable = ['name', 'high_warning_from', 'high_warning_to', 'danger_warning_from', 'danger_warning_to', 'crisis_warning_from', 'crisis_warning_to', 'type_id', 'apply_from'
        , 'apply_to', 'who_created', 'who_updated', 'ip', 'version', 'active', 'high_warning_from_m', 'high_warning_to_m', 'danger_warning_from_m', 'danger_warning_to_m', 'crisis_warning_from_m', 'crisis_warning_to_m', 'is_warning'];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public function brandnameSmsType()
    {
        return $this->belongsTo(BrandnameSmsType::class, 'type_id')->select('id', 'name', 'group_id');
    }

}