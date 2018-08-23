<?php

use Faker\Generator as Faker;

$factory->define(App\Aluno::class, function (Faker $faker) {
    $ativo = $faker->numberBetween($min = 1, $max = 2);
    return [
        'prontuario' => 'cv'.$faker->unique()->numberBetween($min = 1000000, $max = 9000000),
        'nome' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'telefone' => $faker->tollFreePhoneNumber,
        'semestreAno' => $faker->numberBetween($min = 1, $max = 7),
        'Observacoes' => $faker->realText($maxNbChars = 200) ,
        'statusMatricula' => $ativo == 1 ? 'ativo' : 'cancelado',
        'motivoCancelamento' => $ativo == 1 ? '' : $faker->realText($maxNbChars = 200) ,
        'idCurso' => $faker->numberBetween($min = 1, $max = 4) ,
    ];
});
