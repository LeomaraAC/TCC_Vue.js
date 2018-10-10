<?php

use Faker\Generator as Faker;

$factory->define(App\TipoAtendimento::class, function (Faker $faker) {
    return [
        'descricao' => $faker->jobTitle 
    ];
});
