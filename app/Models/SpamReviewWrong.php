<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamReviewWrong extends Model
{
    protected $table = 'spam_review_wrong';

    public $timestamps = true;

    public const CREATED_AT = 'when_created';

    public const UPDATED_AT = 'when_updated';

    public const UNLABEL = 'unlabel';
}