<?php

namespace Tiketux\RancherProjects\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Activitylog\Traits\LogsActivity;
use DB;

class StackServices extends Model
{
	protected $table = "rancher_projects";
    protected $fillable = ["gitlab_url", "rancher_project_id", "remark", "stack_id"];

    public static function simpanRancher($gitlab_url, $rancher_project_id, $remark, $stack_id)
    {
        
        $rancherProject = new StackServices();
        $rancherProject->gitlab_url         = $gitlab_url;
        $rancherProject->rancher_project_id = $rancher_project_id;
        $rancherProject->remark             = $remark;
        $rancherProject->stack_id           = $stack_id;
        $rancherProject->save();
        
        return $rancherProject;
    }

    public static function cekRancher($rancher_project_id)
    {
        $rancherProject = StackServices::where('rancher_project_id', $rancher_project_id)->first();

        return $rancherProject;
    }

    public static function editRancher($gitlab_url, $rancher_project_id, $remark, $stack_id, $id)
    {
        $rancherProject = StackServices::findOrFail($id);
        $rancherProject->update([
            'gitlab_url'         => $gitlab_url,
            'rancher_project_id' => $rancher_project_id,
            'remark'             => $remark,
            'stack_id'           => $stack_id
        ]);

        return $rancherProject;
    }
}