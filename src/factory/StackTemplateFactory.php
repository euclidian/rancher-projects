<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Tiketux\RancherProjects\Models\StackTemplate;

$factory->define(StackTemplate::class, function (Faker $faker) {
    return [
        "name" => $faker->name,
        "docker_compose_yml" => $faker->text(10),
        "rancher_compose_yml" => $faker->text(10)
    ];
});
