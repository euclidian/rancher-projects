<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Tiketux\RancherProjects\Models\RancherProjects;

$factory->define(RancherProjects::class, function (Faker $faker) {
    return [
        //
        'rancher_stack_id' 	=> str_random(5),
        'remark' 			=> $faker->text,
    ];
});
