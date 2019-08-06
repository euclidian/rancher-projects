<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use DB;

class StackTemplate extends Model
{
	protected $table = "stack_template";
    protected $guarded = ["id"];
    public $timestamps = false;
}