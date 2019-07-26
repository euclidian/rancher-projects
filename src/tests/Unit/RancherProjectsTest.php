<?php

namespace Tiketux\RancherProjects\Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tiketux\RancherProjects\Api\Stacks;

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
}
