<?php

namespace Tiketux\RancherProjects\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tiketux\RancherProjects\Models\StackConfig;
use Tiketux\RancherProjects\Models\StackServices;
use Tiketux\RancherProjects\Models\ServiceAssignment;
use Tiketux\UserManagement\Models\UserManagement;

class ServiceAssignmentApi extends Controller
{


  public function __construct()
  {
    $this->middleware(['auth:api', "admin"]);
  }

  public function listAll(Request $r)
  {
    $r->validate([
      "project_id" => "required|integer"
    ]);
    $ss = StackServices::findOrFail($r->input("project_id"));

    $data = ServiceAssignment::where("project_id", $ss->id)->get();
    $response["statusCode"] = 200;
    $response["data"] = $data;

    return response()->json($response);
  }

  public function saveAssignment(Request $r)
  {
    $r->validate([
      "project_id" => "required|integer",
      "users.*.id" => "required|integer"
    ]);
    
    ServiceAssignment::where("project_id", $r->project_id)->delete();
    foreach ($r->input("users") as $item) {
      ServiceAssignment::create([
        "user_id" => $item["id"],
        "project_id" => $r->project_id
      ]);
    }
    $sa = ServiceAssignment::where("project_id", $r->project_id)->get();
    $response["statusCode"] = 200;
    $response["data"] = $sa;

    return response()->json($response);
  }

  public function listServices()
  {
    $data = StackServices::all();
    $response["statusCode"] = 200;
    $response["data"] = $data;

    return response()->json($response);
  }
}
