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
use Tiketux\RancherProjects\Models\RancherProjects;

class RancherProjectsApi extends Controller
{
  

  public function __construct()
  {
  	$this->middleware(['auth:api']);
  }

  public function listAll()
  {
    return response()->json(Rancher::host()->all());
  }

  public function listStackAll()
  {
    $stacks = Rancher::stack()->all();

        foreach ($stacks as $stack)
        {
            unset($stack->dockerCompose);
            unset($stack->rancherCompose);
            unset($stack->healthState);
            unset($stack->environment);
            unset($stack->startOnCreate);
            unset($stack->system);
        
        }
        return response()->json($stacks);
  }

  public function listStackDB()
  {
    $listStackDB = RancherProjects::all();

    $response["statusCode"] = 200;
    $response["data"] = $listStackDB;

    return response()->json($response);
  }

  public function cekStackDB(Request $request)
  {
    $id_stack     = $request->input('id_stack');

    $listStackDB  = RancherProjects::where('rancher_stack_id', $id_stack)->first();

    $response["statusCode"] = 200;
    $response["data"] = $listStackDB;

    return response()->json($response);
  }

  public function addStacktoDB(Request $request)
  {
    $id_stack = $request->input('stack_id');
    $remark   = $request->input('remark');

    $rancherProject = new RancherProjects();

    $addStackDB = $rancherProject->simpan($id_stack, $remark);

    $response["statusCode"] = 200;
    $response["data"] = $addStackDB;

    return response()->json($response);
  }


}