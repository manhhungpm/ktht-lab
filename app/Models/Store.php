<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 04/11/2019
 * Time: 22:05
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'stores';

    protected $fillable = ['id', 'name', 'description', 'status', 'created_at', 'updated_at'];
}