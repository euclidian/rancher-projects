<?php

namespace Tiketux\RancherProjects\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\ClientRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Rancher;
use Benmag\Rancher\Factories\Entity\Stack;

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
    return response()->json(Rancher::stack()->all());
  }


}