<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ServiceAssignment extends Model
{
    protected $table = "service_assignment";
    protected $guarded = [];
    public $timestamps = false;
}
