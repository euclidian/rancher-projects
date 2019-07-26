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

class RancherServiceApi extends Controller
{
  

  public function __construct()
  {
    $this->middleware(['auth:api']);
  }

  public function listServiceOnStack(Request $request)
  {
    $id_stack = $request->input('stack_id');

    $client = new \GuzzleHttp\Client([
      'base_uri' => config('rancher.baseUrl'),
      'auth' => [config('rancher.accessKey'), config('rancher.secretKey')],
    ]);
    $response = $client->get('stack/'.$id_stack.'/services');
    $test = json_decode($response->getBody()->getContents());
    $test1 = $test->data;
    
      foreach ($test1 as $testt) {
        unset($testt->type);
        unset($testt->links);
        unset($testt->actions);
        unset($testt->baseType);
        unset($testt->accountId);
        unset($testt->assignServiceIpAddress);
        unset($testt->createIndex);
        unset($testt->created);
        unset($testt->createdTS);
        unset($testt->currentScale);
        unset($testt->data);
        unset($testt->description);
        unset($testt->externalId);
        unset($testt->fqdn);
        unset($testt->healthState);
        unset($testt->instanceIds);
        unset($testt->kind);
        unset($testt->launchConfig);
        unset($testt->lbConfig);
        unset($testt->linkedServices);
        unset($testt->metadata);
        unset($testt->publicEndpoints);
        unset($testt->removeTime);
        unset($testt->removed);
        unset($testt->retainIp);
        unset($testt->scale);
        unset($testt->scalePolicy);
        unset($testt->secondaryLaunchConfigs);
        unset($testt->selectorContainer);
        unset($testt->selectorLink);
        unset($testt->startOnCreate);
        unset($testt->system);
        unset($testt->transitioning);
        unset($testt->transitioningMessage);
        unset($testt->transitioningProgress);
        unset($testt->upgrade);
        unset($testt->stackId);
        unset($testt->uuid);
        unset($testt->vip);
      }
      $test2["statusCode"] = 200;
      $test2["data"] = $test1;

    return response()->json($test2);
  }

  public function addServicetoDB(Request $request)
  {
    $gitlab_url           = $request->input('url');
    $rancher_project_id   = $request->input('project_id');
    $remark               = $request->input('remark');
    $stack_id             = $request->input('stack_id');

    //cek id stack in db
    $rancherProject = Stacks::findOrFail($stack_id);
    $rancherProject = new StackServices();
    $addServiceDB = $rancherProject->simpanRancher($gitlab_url, $rancher_project_id, $remark, $stack_id);

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
    $id                   = $request->input('id');
    $gitlab_url           = $request->input('url');
    $rancher_project_id   = $request->input('project_id');
    $remark               = $request->input('remark');
    $stack_id             = $request->input('stack_id');

    $rancherProject = Stacks::findOrFail($stack_id);
    $rancherProject = new StackServices();
    $addServiceDB = $rancherProject->editRancher($gitlab_url, $rancher_project_id, $remark, $stack_id, $id);

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
}