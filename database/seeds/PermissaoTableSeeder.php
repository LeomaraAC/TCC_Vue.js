<?php

use Illuminate\Database\Seeder;

class PermissaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('nomeTela'=>'Incluir Grupo', 'siglaTela'=> 'inGru'),
            array('nomeTela'=>'Editar Grupo', 'siglaTela'=> 'edGru'),
            array('nomeTela'=>'Apagar Grupo', 'siglaTela'=> 'exGru'),
            array('nomeTela'=>'Incluir Usuário', 'siglaTela'=> 'inUser'),
            array('nomeTela'=>'Editar Usuário', 'siglaTela'=> 'edUser'),
            array('nomeTela'=>'Apagar Usuário', 'siglaTela'=> 'exUser')
        );
        DB::table('Permissoes')->insert($data);
    }
}
