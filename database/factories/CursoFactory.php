<?php

use Faker\Generator as Faker;

$factory->define(App\Curso::class, function (Faker $faker) {
    return [
        'descricao' => $faker->company,
        'sigla' => $faker->realText($maxNbChars = 10),
    ];
});
