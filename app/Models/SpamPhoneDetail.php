<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamPhoneDetail extends Model
{
    public $timestamps = true;
    public const CREATED_AT = 'when_created';
    public const UPDATED_AT = 'when_updated';
    //action type
    public const ACTION_TYPE_BAN = 'BAN';
    public const ACTION_TYPE_OPEN = 'OPEN';
    public const ACTION_TYPE_ADD_LIST = 'ADD_LIST';
    public const ACTION_TYPE_UNKNOWN = 'UNKNOWN';
    //content type
    public const CONTENT_TYPE_SMS_NORM = 'SMS_NORM';
    public const CONTENT_TYPE_SMS_MOCHA = 'SMS_MOCHA';
    public const CONTENT_TYPE_SMS_SHOPONE = 'SMS_SHOPONE';
    public const CONTENT_TYPE_REASON = 'REASON';
}