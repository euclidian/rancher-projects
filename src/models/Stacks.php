<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use DB;

class Stacks extends Model
{
	protected $table = "rancher_stacks";
    protected $fillable = ["id", "rancher_stack_id", "remark"];

    public static function simpan($id_stack, $remark)
    {
    	$rancherProject = Stacks::where('rancher_stack_id', $id_stack)->first();

        if ($rancherProject == null){
            $rancherProject = new Stacks;
        }

    	$rancherProject->rancher_stack_id	= $id_stack;
    	$rancherProject->remark 			= $remark;
    	$rancherProject->save();

    	return $rancherProject;
    }
}