<?php

namespace Tiketux\RancherProjects\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tiketux\RancherProjects\Models\StackTemplate;
use Tiketux\UserManagement\Middlewares\IsAdmin;

class RancherTemplateApi extends Controller
{


  public function __construct()
  {
    $this->middleware(['auth:api', IsAdmin::class]);
  }

  public function listAll()
  {
    $data = StackTemplate::all();
    $response["statusCode"] = 200;
    $response["data"] = $data;

    return response()->json($response);
  }

  public function saveTemplate(Request $r)
  {
    $r->validate([
      "name" => "required|string",
      "docker_compose_yml" => "required|string",
      "rancher_compose_yml" => "required|string"
    ]);
    $st = new StackTemplate;
    if ($r->template_id) {
      $st = StackTemplate::findOrFail($r->template_id);
    }
    $st->name = $r->name;
    $st->docker_compose_yml = $r->docker_compose_yml;
    $st->rancher_compose_yml = $r->rancher_compose_yml;
    $st->save();

    $response["statusCode"] = 200;
    $response["data"] = $st;

    return response()->json($response);
  }

  public function deleteTemplate($id)
  {
    $st = StackTemplate::findOrFail($id);
    $st->delete();
    $response["statusCode"] = 200;
    $response["data"] = $st;

    return response()->json($response);
  }

  public function detailTemplate($id)
  {
    $st = StackTemplate::findOrFail($id);
    
    $response["statusCode"] = 200;
    $response["data"] = $st;

    return response()->json($response);
  }
}
