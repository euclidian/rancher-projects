<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use DB;

class RancherProjects extends Model
{
	protected $table = "rancher_stacks";
    protected $fillable = ["id", "rancher_stack_id", "remark"];

    public static function simpan($id_stack, $remark)
    {
    	$rancherProject = RancherProjects::where('rancher_stack_id', $id_stack)->first();

        if ($rancherProject == null){
            $rancherProject = new RancherProjects;
        }

    	$rancherProject->rancher_stack_id	= $id_stack;
    	$rancherProject->remark 			= $remark;
    	$rancherProject->save();

    	return $rancherProject;
    }

    public static function simpanRancher($gitlab_url, $rancher_project_id, $remark, $stack_id)
    {
        $rancherProject = DB::table('rancher_projects')->insert([
            'gitlab_url'         => $gitlab_url, 
            'rancher_project_id' => $rancher_project_id,
            'remark'             => $remark,
            'stack_id'           => $stack_id
        ]);

        return $rancherProject;
    }

    public static function cekRancher($rancher_project_id)
    {
        $rancherProject = DB::table('rancher_projects')
                   ->select('*')
                   ->where('rancher_project_id', $rancher_project_id)
                   ->first();

        return $rancherProject;
    }
}