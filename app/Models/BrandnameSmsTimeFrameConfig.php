<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandnameSmsTimeFrameConfig extends Model
{

    protected $fillable = ['type_id','apply_from','apply_to','week_day','time_frame'];

    protected $dateFormat = 'Y-m-d H:i:s';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public function brandnameSmsType(){
        return $this->belongsTo(BrandnameSmsType::class,'type_id')->select('id','name','group_id');
    }

}