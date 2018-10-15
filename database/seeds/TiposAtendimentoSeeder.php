<?php

use Illuminate\Database\Seeder;

class TiposAtendimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('descricao' => 'tipo existente'),
            array('descricao' => 'tipo repedido'),
            array('descricao' => 'tipo editar'),
            array('descricao' => 'tipo apagar'),
        );
        DB::table('tipo_atendimento')->insert($data);
    }
}
