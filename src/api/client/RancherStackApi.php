<?php

namespace Tiketux\RancherProjects\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Benmag\Rancher\Facades\Rancher;
use Benmag\Rancher\Factories\Entity\Stack;
use Tiketux\RancherProjects\Models\Stacks;
use Tiketux\RancherProjects\Models\StackServices;
use Tiketux\RancherProjects\Models\StackConfig;

class RancherStackApi extends Controller
{


  public function __construct()
  {
    $this->middleware('client');
  }

  public function createStack(Request $request)
  {
    $request->validate([
      "name" => "required|string",
      "description" => "required|string",
      "config_id" => "required|integer"
    ]);

    $config = StackConfig::findOrFail($request->config_id);

    $stack = new \Benmag\Rancher\Factories\Entity\Stack();
    $stack->name = $request->name;
    $stack->description = $request->description;
    $stack->dockerCompose = $config->generated_docker_compose_yml;
    $stack->rancherCompose = $config->generated_rancher_compose_yml;
    $data = Rancher::stack()->project(config("rancher_projects.account_id"))->create($stack);

    $response["statusCode"] = 200;
    $response["data"] = $data;
    return response()->json($response);
  }
}
