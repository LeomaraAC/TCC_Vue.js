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
            array('descricao'=>'Análise e Desenvolvimento de Sistema','sigla'=>'ADS'),
            array('descricao'=>'Processos Quimico','sigla'=>'PSQ'),
            array('descricao'=>'Licenciatura Quimica','sigla'=>'LCQ'),
            array('descricao'=>'Integrado de Quimica','sigla'=>'ITQ')
        );
        DB::table('cursos')->insert($data);
    }
}
