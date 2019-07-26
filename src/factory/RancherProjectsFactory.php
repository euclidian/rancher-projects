<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Illuminate\Support\Str;
use Faker\Generator as Faker;
use Tiketux\RancherProjects\Models\Stacks;

$factory->define(Stacks::class, function (Faker $faker) {
    return [
        //
        'rancher_stack_id' 	=> Str::random(10),
        'remark' 			=> $faker->text,
    ];
});
