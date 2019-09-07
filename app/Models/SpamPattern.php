<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamPattern extends Model
{
    protected $fillable = ['who_created', 'who_updated', 'active', 'content', 'tlsh', 'count_content', 'count_spam',
        'count_calling', 'count_called', 'accuse_id', 'accuse_msisdn', 'accuse_msisdn_spam', 'accused_time', 'application_name',
        'label_name', 'sync', 'relabel', 'version', 'sys_period_start', 'sys_period_end'];

    protected $hidden = ['pivot'];

    protected $dateFormat = 'Y-m-d H:i:s.u';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public const UNLABEL = 'unlabel';

    public const IGNORE = 'ignore';

    public const HAM = 'ham';

    public const SPAM = 'spam';

    public function spamApplication()
    {
        return $this->hasOne(SpamApplication::class, 'name', 'application_name');
    }

    public function spamLabels(){
        return $this->hasOne(SpamLabel::class,'name','label_name');
    }


}