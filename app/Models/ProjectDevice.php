<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:14
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectDevice extends Model
{
    protected $table = 'project_device';

    protected $fillable = ['project_id', 'device_id'];
}