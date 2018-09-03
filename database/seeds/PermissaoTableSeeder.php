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
            array('descricao'=>'Incluir aluno', 'modulo'=>'aluno','nome'=> 'incluir_Aluno'),
            array('descricao'=>'Editar aluno', 'modulo'=>'aluno','nome'=> 'editar_Aluno'),
            array('descricao'=>'Apagar aluno','modulo'=>'aluno', 'nome'=> 'excluir_Aluno'),
            array('descricao'=>'Trancar matricula do aluno','modulo'=>'aluno', 'nome'=> 'trancar_Aluno'),
            array('descricao'=>'Ativar matricula do aluno','modulo'=>'aluno', 'nome'=> 'ativar_Aluno'),
            array('descricao'=>'Finalizar matricula do aluno','modulo'=>'aluno', 'nome'=> 'finalizar_Aluno'),
            array('descricao'=>'Visualizar aluno','modulo'=>'aluno', 'nome'=> 'visualizar_Aluno')
        );
        DB::table('Permissoes')->insert($data);
    }
}
