<?php

namespace Tiketux\RancherProjects\Api\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Benmag\Rancher\Facades\Rancher;
use Benmag\Rancher\Factories\Entity\ContainerExec;
use Benmag\Rancher\Factories\Entity\Stack;
use Tiketux\RancherProjects\Models\Stacks;
use Tiketux\RancherProjects\Models\StackServices;
use Tiketux\RancherProjects\Models\ServiceEnv;

class RancherServiceApi extends Controller
{
  public function __construct()
  {
    $this->middleware("client");
  }

  public function executeCommandStack(Request $request)
  {
    $request->validate([
      "container_id" => "required|string",
      "commands" => "required|array",
      "commands.*" => "required|string"
    ]);
    $container_id = $request->input('container_id');
    $ce = new ContainerExec();
    $ce->command = [
      "/bin/sh",
      "-c"
    ];
    foreach ($request->commands as $item) {
      array_push($ce->command, $item);
    }
    $result = Rancher::container()->execute($container_id, $ce);

    \Ratchet\Client\connect($result->url . "?token=" . $result->token)->then(function ($conn) {

      $conn->on('message', function ($msg) use ($conn) {
        echo "Received: {$msg}\n";
        $conn->close();
      });
    }, function ($e) {
      echo "Could not connect: {$e->getMessage()}\n";
    });
    $response["statusCode"] = 200;
    $response["data"] = $result;

    return response()->json($response);
  }
}
