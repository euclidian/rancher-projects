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

class RancherStackApi extends Controller
{
  

  public function __construct()
  {
    $this->middleware(['auth:api']);
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

  public function deleteStackinDB(Request $request)
  {
    $id_stack = $request->input('stack_id');

    $rancherProject = RancherProjects::where("rancher_stack_id", $id_stack);
    $rancherProject->delete();

    $response["statusCode"] = 200;
    $response["data"] = $id_stack;
    return response()->json($response);

  }

  public function listServiceOnStack(Request $request)
  {
    $id_stack = $request->input('stack_id');

    $client = new \GuzzleHttp\Client([
      'base_uri' => config('rancher.baseUrl'),
      'auth' => [config('rancher.accessKey'), config('rancher.secretKey')],
    ]);
    $response = $client->get('stack/'.$id_stack.'/services');
    return response()->json(json_decode($response->getBody()->getContents()));
  }

  public function addServicetoDB(Request $request)
  {
    $gitlab_url           = $request->input('url');
    $rancher_project_id   = $request->input('project_id');
    $remark               = $request->input('remark');
    $stack_id             = $request->input('stack_id');

    //cek id stack in db
    $rancherProject = RancherProjects::where('rancher_stack_id', $stack_id)->first();
    $stack_id = $rancherProject->id;

    $rancherProject = new RancherProjects();
    $addServiceDB = $rancherProject->simpanRancher($gitlab_url, $rancher_project_id, $remark, $stack_id);

    $response["statusCode"] = 200;
    $response["data"] = $addServiceDB;

    return response()->json($response);
  }

  public function cekServiceOnDB(Request $request)
  {
    $rancher_project_id   = $request->input('project_id');

    $rancherProject = new RancherProjects();
    $cekServiceDB = $rancherProject->cekRancher($rancher_project_id);

    $response["statusCode"] = 200;
    $response["data"] = $cekServiceDB;

    return response()->json($response);
  }


}