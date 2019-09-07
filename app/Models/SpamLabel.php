<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamLabel extends Model
{
    protected $table = 'spam_labels';

    protected $fillable = ['name', 'parent', 'display_name'];

    protected $hidden = ['pivot'];

    public $timestamps = false;


}
