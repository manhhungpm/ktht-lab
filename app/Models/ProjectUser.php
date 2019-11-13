<?php
/**
 * Created by PhpStorm.
 * User: manhhungpm
 * Date: 09/11/2019
 * Time: 23:13
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUser extends Model
{
    protected $table = 'project_user';

    protected $fillable = ['project_id', 'user_id'];
}