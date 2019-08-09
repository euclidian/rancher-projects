<?php

namespace Tiketux\RancherProjects\Api;

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
    $this->middleware(['auth:api',"admin"]);
  }

  public function listStackAll()
  {
    $stacks = Rancher::stack()->all();

    foreach ($stacks as $stack) {
      unset($stack->dockerCompose);
      unset($stack->rancherCompose);
      unset($stack->healthState);
      unset($stack->environment);
      unset($stack->startOnCreate);
      unset($stack->system);
    }
    return response()->json($stacks);
  }

  public function detailStackOnline(Request $request)
  {
    $stack_id = $request->input('stack_id');

    $stacks = Rancher::stack()->get($stack_id);

    unset($stacks->dockerCompose);
    unset($stacks->rancherCompose);
    unset($stacks->healthState);
    unset($stacks->environment);
    unset($stacks->startOnCreate);
    unset($stacks->system);

    $response["statusCode"] = 200;
    $response["data"] = $stacks;

    return response()->json($response);
  }

  public function listStackDB()
  {
    $listStackDB = Stacks::all();

    $response["statusCode"] = 200;
    $response["data"] = $listStackDB;

    return response()->json($response);
  }

  public function cekStackDB(Request $request)
  {
    $id_stack     = $request->input('id_stack');

    $listStackDB  = Stacks::where('rancher_stack_id', $id_stack)->first();

    $response["statusCode"] = 200;
    $response["data"] = $listStackDB;

    return response()->json($response);
  }

  public function addStacktoDB(Request $request)
  {
    $id_stack = $request->input('stack_id');
    $remark   = $request->input('remark');

    $rancherProject = new Stacks();

    $addStackDB = $rancherProject->simpan($id_stack, $remark);

    $response["statusCode"] = 200;
    $response["data"] = $addStackDB;

    return response()->json($response);
  }

  public function deleteStackinDB(Request $request)
  {
    $id_stack = $request->input('stack_id');

    $rancherProject = Stacks::where("rancher_stack_id", $id_stack);
    $rancherProject->delete();

    $response["statusCode"] = 200;
    $response["data"] = $id_stack;
    return response()->json($response);
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
