<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use DB;

class ServiceEnv extends Model
{
    protected $table = "service_env";
    protected $guarded = [];
    public $timestamps = false;
}
