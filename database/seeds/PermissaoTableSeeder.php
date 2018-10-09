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
            array('descricao'=>'Incluir grupo','modulo'=>'grupo', 'nome'=> 'incluir_Grupo'),
            array('descricao'=>'Editar grupo','modulo'=>'grupo', 'nome'=> 'editar_Grupo'),
            array('descricao'=>'Apagar grupo','modulo'=>'grupo', 'nome'=> 'excluir_Grupo'),
            array('descricao'=>'Visualizar grupo','modulo'=>'grupo', 'nome'=> 'visualizar_Grupo'),
            array('descricao'=>'Incluir usuário', 'modulo'=>'usuario','nome'=> 'incluir_User'),
            array('descricao'=>'Editar usuário', 'modulo'=>'usuario','nome'=> 'editar_User'),
            array('descricao'=>'Apagar usuário','modulo'=>'usuario', 'nome'=> 'excluir_User'),
            array('descricao'=>'Visualizar usuário','modulo'=>'usuario', 'nome'=> 'visualizar_User'),
            array('descricao'=>'Importar dados dos alunos','modulo'=>'aluno', 'nome'=> 'importar_Alunos'),
            array('descricao'=>'Visualizar aluno','modulo'=>'aluno', 'nome'=> 'visualizar_Aluno'),
            array('descricao'=>'incluir tipo de atendimento','modulo'=>'tipo_atendimento', 
                  'nome'=> 'incluir_Tipo_Atendimento'),
            array('descricao'=>'Editar tipo de atendimento','modulo'=>'tipo_atendimento', 
                  'nome'=> 'editar_Tipo_Atendimento'),
            array('descricao'=>'Apagar tipo de atendimento','modulo'=>'tipo_atendimento', 
                  'nome'=> 'excluir_Tipo_Atendimento'),
        );
        DB::table('permissoes')->insert($data);
    }
}
