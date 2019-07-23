<?php

namespace Tiketux\RancherProjects\Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tiketux\RancherProjects\Api\RancherProjectsApi;

class RancherProjectsTest extends PassportTestCase
{
  use DatabaseTransactions;
  use WithFaker;

  public $baseUrl   = 'http://127.0.0.1';

  public function testListAll()
  {
    $id = str_random(3);
    $name = $this->faker->name;
    $description = $this->faker->text;
    $hostname = $this->faker->name;
    $accountId = str_random(3);
    $state = "active";
    $mock = $this->mock(\Benmag\Rancher\Rancher::class, function ($m) use (
      $id,
      $name,
      $description,
      $hostname,
      $accountId,
      $state
    ) {
      $m->shouldReceive('host->all')->once()->andReturn([[
        "id" => $id,
        "name" => $name,
        "description" => $description,
        "hostname" => $hostname,
        "accountId" => $accountId,
        "state" => $state
      ]]);
    });

    $response = $this->get("/tiketux/rancherprojects/api/list");
    $response->assertStatus(200)
      ->assertJson([[
        "id" => $id,
        "name" => $name,
        "description" => $description,
        "hostname" => $hostname,
        "accountId" => $accountId,
        "state" => $state
      ]]);
  }

  public function testListStackAll()
  {
    $id           = str_random(3);
    $name         = $this->faker->name;
    $description  = $this->faker->text;
    $hostname     = $this->faker->name;
    $accountId    = str_random(3);
    $state        = "active";

    $mock = $this->mock(\Benmag\Rancher\Rancher::class, function ($m) use (
      $id,
      $name,
      $description,
      $hostname,
      $accountId,
      $state
    ) {
      $m->shouldReceive('stack->all')->once()->andReturn([[
        "id"          => $id,
        "name"        => $name,
        "description" => $description,
        "hostname"    => $hostname,
        "accountId"   => $accountId,
        "state"       => $state
      ]]);
    });

    $response = $this->get("/tiketux/rancherprojects/api/liststack");
    $response->assertStatus(200)
      ->assertJson([[
        "id"          => $id,
        "name"        => $name,
        "description" => $description,
        "hostname"    => $hostname,
        "accountId"   => $accountId,
        "state"       => $state
      ]]);
  }

  public function testAddStacktoDB()
  {
    $id_stack = "12345";
    $remark   = "abcd";

    $response = $this->post("/tiketux/rancherprojects/api/addstackdb",[
                "stack_id"  => $id_stack,
                "remark"    => $remark
              ]);
    $response->assertStatus(200)
      ->assertJson([
        "statusCode" => 200,
        "data"  => [
          "rancher_stack_id"  => $id_stack,
          "remark"            => $remark
        ]
      ]);

    $this->assertDatabaseHas('rancher_stacks', [
      "rancher_stack_id"  => $id_stack,
      "remark"            => $remark
    ]);
  }

  public function testListStackDB()
  {
    $response = $this->get("/tiketux/rancherprojects/api/liststackdb");

    $response->assertStatus(200)
      ->assertJson([
        "statusCode"  => 200,
        "data"        =>[[
          "rancher_stack_id"  => $this->stack->rancher_stack_id,
          "remark"            => $this->stack->remark
        ]]
      ]);
  }

  public function testCekStackDB()
  {
    $id_stack = $this->stack->rancher_stack_id;

    $response = $this->post("/tiketux/rancherprojects/api/cekstackdb", [
                "id_stack"  => $id_stack
              ]);

    $response->assertStatus(200)
      ->assertJson([
        "statusCode" => 200,
          "data"        =>[
          "rancher_stack_id"  => $this->stack->rancher_stack_id,
          "remark"            => $this->stack->remark
        ]
      ]);
  }
}
