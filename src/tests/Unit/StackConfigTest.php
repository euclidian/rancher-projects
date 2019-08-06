<?php

namespace Tiketux\RancherProjects\Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tiketux\RancherProjects\Api\Stacks;
use Tiketux\RancherProjects\Models\StackConfig;
use Tiketux\UserManagement\Models\UserManagement;
use Tiketux\RancherProjects\Models\StackTemplate;

class StackConfigTest extends PassportTestCase
{
  use DatabaseTransactions;
  use WithFaker;

  public $baseUrl   = 'http://127.0.0.1';

  public function testListAll()
  {
    $st = factory(StackTemplate::class)->create();
    factory(StackConfig::class)->create([
      "template_id" => $st->id
    ]);

    $response = $this->get("/tiketux/rancherprojects/api/config/list");

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [[
          "id",
          "name",
          "generated_docker_compose_yml",
          "generated_rancher_compose_yml"
        ]]
      ]);
  }

  public function testListAllNonAdmin()
  {
    $user = UserManagement::findOrFail($this->user->id);
    $user->is_admin = 0;
    $user->save();

    $st = factory(StackTemplate::class)->create();
    factory(StackConfig::class)->create([
      "template_id" => $st->id
    ]);

    $response = $this->get("/tiketux/rancherprojects/api/config/list");

    $response->assertStatus(403);
  }

  public function testDetailConfig()
  {
    $st = factory(StackTemplate::class)->create();
    $config = factory(StackConfig::class)->create([
      "template_id" => $st->id
    ]);

    $response = $this->get("/tiketux/rancherprojects/api/config/detail/" . $config->id);

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [
          "id",
          "name",
          "generated_docker_compose_yml",
          "generated_rancher_compose_yml"
        ]
      ]);
  }

  public function testDetailConfigDataTidakAda()
  {
    $st = factory(StackTemplate::class)->create();
    $config = factory(StackConfig::class)->create([
      "template_id" => $st->id
    ]);

    $response = $this->get("/tiketux/rancherprojects/api/config/detail/" . ($config->id + rand(1000, 2000)));

    $response->assertStatus(404);
  }

  public function testDeleteConfig()
  {
    $st = factory(StackTemplate::class)->create();
    $config = factory(StackConfig::class)->create([
      "template_id" => $st->id
    ]);

    $response = $this->get("/tiketux/rancherprojects/api/config/delete/" . $config->id);

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [
          "id",
          "name",
          "generated_docker_compose_yml",
          "generated_rancher_compose_yml"
        ]
      ]);

    $this->assertDatabaseMissing("stack_config", [
      "id" => $config->id
    ]);
  }

  public function testSaveConfig()
  {
    $st = factory(StackTemplate::class)->create();
    $name = $this->faker->name;
    $generated_docker_compose_yml = $this->faker->text(10);
    $generated_rancher_compose_yml = $this->faker->text(10);
    $response = $this->post("/tiketux/rancherprojects/api/config/save", [
      "template_id" => $st->id,
      "name" => $name,
      "configs"=>[
        "docker"=>[],
        "rancher"=>[]
      ]
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [
          "id",
          "name",
          "generated_docker_compose_yml",
          "generated_rancher_compose_yml"
        ]
      ]);

    $this->assertDatabaseHas("stack_config", [
      "name" => $name
    ]);
  }

  public function testSaveConfigDataTidakValid()
  {
    $name = $this->faker->name;
    $generated_docker_compose_yml = $this->faker->text(10);
    $generated_rancher_compose_yml = $this->faker->text(10);
    $response = $this->post("/tiketux/rancherprojects/api/config/save", [
      "name" => $name,
      "generated_docker_compose_yml" => $generated_docker_compose_yml
    ]);

    $response->assertStatus(422);

    $this->assertDatabaseMissing("stack_config", [
      "name" => $name,
      "generated_docker_compose_yml" => $generated_docker_compose_yml,
      "generated_rancher_compose_yml" => $generated_rancher_compose_yml
    ]);
  }
}
