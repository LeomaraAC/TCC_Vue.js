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
        $idGrupo = DB::table('grupo')->insertGetId(
            ['nomeGrupo' => 'Master']
        );

        $count = DB::table('permissoes')->count();
        for ($i=1; $i <= $count ; $i++) { 
            DB::table('permissoes_grupo')->insert(
                [
                    'idGrupo' => $idGrupo,
                    'idTelas' => $i
                ]
            );
        }

        // factory(App\Grupo::class)->create()->each(function($a) {
        //     $a->funcoes()->attach(App\Permissao::all()->random(13));
        // });
    }
}
