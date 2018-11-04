<?php

use Illuminate\Database\Seeder;

class GrupoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grupo = App\Grupo::create(
            ['nomeGrupo' => 'Master']
        );

        $count = App\Permissao::all()->count();
        $grupo->funcoes()->attach(App\Permissao::all());

        App\Grupo::insert([
            ['nomeGrupo' => 'Grupo Repetido'],
            ['nomeGrupo' => 'Grupo Editar'],
            ['nomeGrupo' => 'Grupo Existente'],
            ['nomeGrupo' => 'Grupo Editar sem alterar permissões'],
            ['nomeGrupo' => 'Grupo Excluir']
        ]);
        $grupos = App\Grupo::where('nomeGrupo', '!=', 'Master')->get();
        $grupos->each(function($a) use($count) {
            $num = rand(1,$count);
            $a->funcoes()->attach(App\Permissao::all()->random($num));
        });

        // factory(App\Grupo::class)->create()->each(function($a) {
        //     $a->funcoes()->attach(App\Permissao::all()->random(13));
        // });
    }
}
