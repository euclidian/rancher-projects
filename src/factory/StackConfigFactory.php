<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Tiketux\RancherProjects\Models\StackConfig;

$factory->define(StackConfig::class, function (Faker $faker) {
    return [
        "template_id" => rand(1000, 2000),
        "name" => $faker->name,
        "generated_docker_compose_yml" => $faker->text(10),
        "generated_rancher_compose_yml" => $faker->text(10)
    ];
});
