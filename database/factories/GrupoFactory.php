<?php

use Faker\Generator as Faker;

$factory->define(App\Grupo_Usuario::class, function (Faker $faker) {
    return [
        'nomeGrupo'=> $faker->jobTitle
    ];
});
