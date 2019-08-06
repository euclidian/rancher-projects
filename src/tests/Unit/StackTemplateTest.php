<?php

namespace Tiketux\RancherProjects\Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tiketux\RancherProjects\Api\Stacks;
use Tiketux\RancherProjects\Models\StackTemplate;
use Tiketux\UserManagement\Models\UserManagement;

class StackTemplateTest extends PassportTestCase
{
  use DatabaseTransactions;
  use WithFaker;

  public $baseUrl   = 'http://127.0.0.1';

  public function testListAll()
  {
    factory(StackTemplate::class)->create();

    $response = $this->get("/tiketux/rancherprojects/api/template/list");

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [[
          "id",
          "name",
          "docker_compose_yml",
          "rancher_compose_yml"
        ]]
      ]);
  }

  public function testListAllNonAdmin()
  {
    $user = UserManagement::findOrFail($this->user->id);
    $user->is_admin = 0;
    $user->save();

    factory(StackTemplate::class)->create();

    $response = $this->get("/tiketux/rancherprojects/api/template/list");

    $response->assertStatus(403);
  }

  public function testDetailTemplate()
  {
    $template = factory(StackTemplate::class)->create();

    $response = $this->get("/tiketux/rancherprojects/api/template/detail/" . $template->id);

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [
          "id",
          "name",
          "docker_compose_yml",
          "rancher_compose_yml"
        ]
      ]);
  }

  public function testDetailTemplateDataTidakAda()
  {
    $template = factory(StackTemplate::class)->create();

    $response = $this->get("/tiketux/rancherprojects/api/template/detail/" . ($template->id + rand(1000, 2000)));

    $response->assertStatus(404);
  }

  public function testDeleteTemplate()
  {
    $template = factory(StackTemplate::class)->create();

    $response = $this->get("/tiketux/rancherprojects/api/template/delete/" . $template->id);

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [
          "id",
          "name",
          "docker_compose_yml",
          "rancher_compose_yml"
        ]
      ]);

    $this->assertDatabaseMissing("stack_template", [
      "id" => $template->id
    ]);
  }

  public function testSaveTemplate()
  {
    $name = $this->faker->name;
    $docker_compose_yml = $this->faker->text(10);
    $rancher_compose_yml = $this->faker->text(10);
    $response = $this->post("/tiketux/rancherprojects/api/template/save", [
      "name" => $name,
      "docker_compose_yml" => $docker_compose_yml,
      "rancher_compose_yml" => $rancher_compose_yml
    ]);

    $response->assertStatus(200)
      ->assertJsonStructure([
        "statusCode",
        "data" => [
          "id",
          "name",
          "docker_compose_yml",
          "rancher_compose_yml"
        ]
      ]);

    $this->assertDatabaseHas("stack_template", [
      "name" => $name,
      "docker_compose_yml" => $docker_compose_yml,
      "rancher_compose_yml" => $rancher_compose_yml
    ]);
  }

  public function testSaveTemplateDataTidakValid()
  {
    $name = $this->faker->name;
    $docker_compose_yml = $this->faker->text(10);
    $rancher_compose_yml = $this->faker->text(10);
    $response = $this->post("/tiketux/rancherprojects/api/template/save", [
      "name" => $name,
      "docker_compose_yml" => $docker_compose_yml
    ]);

    $response->assertStatus(422);

    $this->assertDatabaseMissing("stack_template", [
      "name" => $name,
      "docker_compose_yml" => $docker_compose_yml,
      "rancher_compose_yml" => $rancher_compose_yml
    ]);
  }
}
