<?php

use Illuminate\Database\Seeder;

class CursoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('descricao'=>'Análise e Desenvolvimento de Sistema','sigla'=>'ADS','duracao' => '6'),
            array('descricao'=>'Processos Quimico','sigla'=>'PSQ','duracao' => '4'),
            array('descricao'=>'Licenciatura Quimica','sigla'=>'LCQ','duracao' => '7'),
            array('descricao'=>'Integrado de Quimica','sigla'=>'ITQ','duracao' => '3')
        );
        DB::table('cursos')->insert($data);
    }
}
