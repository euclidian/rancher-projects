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

}