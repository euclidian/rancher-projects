<?php

namespace Tiketux\RancherProjects\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Tiketux\RancherProjects\Models\StackConfig;
use Tiketux\UserManagement\Middlewares\IsAdmin;
use Tiketux\RancherProjects\Models\StackTemplate;

class RancherConfigApi extends Controller
{


  public function __construct()
  {
    $this->middleware("client");
  }

  public function listAll()
  {
    $data = StackConfig::all();
    $response["statusCode"] = 200;
    $response["data"] = $data;

    return response()->json($response);
  }

  public function saveConfig(Request $r)
  {
    $r->validate([
      "template_id" => "required|integer",
      "name" => "required|string",
      "configs.rancher.*.key" => "required|string",
      "configs.docker.*.key" => "required|string",
      "configs.rancher.*.value" => "required|string",
      "configs.docker.*.value" => "required|string"
    ]);
    $sc = StackConfig::saveConfig(
      $r->template_id,
      $r->name,
      $r->configs["docker"],
      $r->configs["rancher"]
    );

    $response["statusCode"] = 200;
    $response["data"] = $sc;

    return response()->json($response);
  }
}
