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
use Tiketux\RancherProjects\Models\ServiceEnv;

class RancherServiceApi extends Controller
{


  public function __construct()
  {
    $this->middleware(['auth:api', "admin"]);
  }

  public function listServiceOnStack(Request $request)
  {
    $id_stack = $request->input('stack_id');

    $client = new \GuzzleHttp\Client([
      'base_uri' => config('rancher.baseUrl'),
      'auth' => [config('rancher.accessKey'), config('rancher.secretKey')],
    ]);
    $response = $client->get('stack/' . $id_stack . '/services');
    $service = json_decode($response->getBody()->getContents());
    $dataService = $service->data;

    foreach ($dataService as $dataServiceStack) {
      $data[] = [
        'id'    => $dataServiceStack->id,
        'name'  => $dataServiceStack->name,
        'state' => $dataServiceStack->state
      ];
    }
    $responseData["statusCode"] = 200;
    $responseData["data"] = $data;

    return response()->json($responseData);
  }

  public function addServicetoDB(Request $request)
  {
    $request->validate([
      'url' => "required",
      'project_id' => "required",
      'remark' => "required",
      'stack_id' => "required",
      'name' => "required"
    ]);


    $gitlab_url           = $request->input('url');
    $rancher_project_id   = $request->input('project_id');
    $remark               = $request->input('remark');
    $stack_id             = $request->input('stack_id');
    $name                 = $request->input('name');

    //cek id stack in db
    $rancherProject = Stacks::findOrFail($stack_id);
    $rancherProject = new StackServices();
    $addServiceDB = $rancherProject->simpanRancher($gitlab_url, $rancher_project_id, $remark, $stack_id, $name);

    $response["statusCode"] = 200;
    $response["data"] = $addServiceDB;

    return response()->json($response);
  }

  public function cekServiceOnDB(Request $request)
  {
    $rancher_project_id   = $request->input('project_id');

    $rancherProject = new StackServices();
    $cekServiceDB = $rancherProject->cekRancher($rancher_project_id);

    $response["statusCode"] = 200;
    $response["data"] = $cekServiceDB;

    return response()->json($response);
  }

  public function detailServiceStackOnDB(Request $request)
  {
    $stack_id   = $request->input('stack_id');

    $rancherProject = new StackServices();
    $detailServiceStackDB = StackServices::where('stack_id', $stack_id)->get();

    $response["statusCode"] = 200;
    $response["data"] = $detailServiceStackDB;

    return response()->json($response);
  }

  public function deleteServiceStackOnDB(Request $request)
  {
    $id   = $request->input('id');

    $rancherProject = StackServices::findOrFail($id);
    $rancherProject->delete();

    $response["statusCode"] = 200;
    $response["data"] = $id;

    return response()->json($response);
  }

  public function updateServicetoDB(Request $request)
  {
    $request->validate([
      'id' => "required",
      'url' => "required",
      'project_id' => "required",
      'remark' => "required",
      'stack_id' => "required",
      'name' => "required"
    ]);

    $id                   = $request->input('id');
    $gitlab_url           = $request->input('url');
    $rancher_project_id   = $request->input('project_id');
    $remark               = $request->input('remark');
    $name               = $request->input('name');
    $stack_id             = $request->input('stack_id');

    $rancherProject = Stacks::findOrFail($stack_id);
    $rancherProject = new StackServices();
    $addServiceDB = $rancherProject->editRancher($gitlab_url, $rancher_project_id, $remark, $name, $stack_id, $id);

    $response["statusCode"] = 200;
    $response["data"] = $addServiceDB;

    return response()->json($response);
  }

  public function detailServiceOnDB(Request $request)
  {
    $id   = $request->input('id');

    $rancherProject = StackServices::findOrFail($id);

    $response["statusCode"] = 200;
    $response["data"] = $rancherProject;

    return response()->json($response);
  }

  public function detailServiceOnline(Request $request)
  {
    $id   = $request->input('id');
    $ss = StackServices::findOrFail($id);

    if (!ServiceEnv::where("project_id", $ss->id)->first()) {
      $rancherProject = Rancher::Service()->get($ss->rancher_project_id);
      foreach ((array) $rancherProject->launchConfig->environment as $key => $value) {
        ServiceEnv::create([
          "project_id" => $ss->id,
          "project_online_id" => $ss->rancher_project_id,
          "key" => $key,
          "value" => $value
        ]);
      }
    }
    $result = ServiceEnv::where("project_id", $ss->id)->get();
    $response["statusCode"] = 200;
    $response["data"] = $result;

    return response()->json($response);
  }
}
