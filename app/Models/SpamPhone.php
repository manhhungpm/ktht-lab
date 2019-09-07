<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamPhone extends Model
{
    public $timestamps = true;

    public const CREATED_AT = 'when_created';
    public const UPDATED_AT = 'when_updated';
    public const LIST_TYPE = [
        'WHITE' => 'WHITE',
        'BLACK' => 'BLACK',
        'VIP' => 'VIP',
        'NONE' => 'NONE',
        'FRAUD' => 'FRAUD',
        'War1' => 'War1',
        'War2' => 'War2',
        'OPEN' => 'OPEN '];
    //State
    public const STATE_DOUBTING = 'DOUBTING';
    public const STATE_PROPOSE_BAN = 'PROPOSE_BAN';
    public const STATE_WAITING_BAN = 'WAITING_BAN';
    public const STATE_BANNED = 'BANNED';
    public const STATE_PROPOSE_OPEN = 'PROPOSE_OPEN';
    public const STATE_WAITING_OPEN = 'WAITING_OPEN';
    public const STATE_INLINE = 'INLINE';
    //Carrier
    public const CARRIER_VIETTEL = 'VIETTEL';
    public const CARRIER_VINAPHONE = 'VINAPHONE';
    public const CARRIER_MOBIPHONE = 'MOBIPHONE';
    public const CARRIER_VIETNAMMOBILE = 'VIETNAMMOBILE';
    public const CARRIER_GMOBILE = 'GMOBILE';
    public const CARRIER_OTHER = 'OTHER';
}