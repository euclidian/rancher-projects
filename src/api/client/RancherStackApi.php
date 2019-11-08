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

  public function detailStackOnline(Request $request)
  {
    $request->validate([
      "stack_id" => "required|string"
    ]);
    $stack_id = $request->input('stack_id');

    $stacks = Rancher::stack()->get($stack_id);
    $client = new \GuzzleHttp\Client([
      'base_uri' => config('rancher.baseUrl'),
      'auth' => [config('rancher.accessKey'), config('rancher.secretKey')],
    ]);

    $response = $client->get('stack/' . $stack_id . '/services');
    $service = json_decode($response->getBody()->getContents());

    unset($stacks->dockerCompose);
    unset($stacks->rancherCompose);
    unset($stacks->environment);
    unset($stacks->startOnCreate);
    unset($stacks->system);
    $stacks->services = [];

    foreach ($service->data as $item) {
      foreach ($item->instanceIds as $ids) {
        if (Rancher::container()->get($ids)->state == "running") {
          $instanceIds = $ids;
          break;
        }
      }
      array_push($stacks->services, [
        "id" => $item->id,
        "name" => $item->name,
        "healthState" => $item->healthState,
        "containerId" => $instanceIds
      ]);
    }

    return response()->json($stacks);
  }
}
