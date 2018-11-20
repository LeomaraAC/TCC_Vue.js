<?php

use Illuminate\Database\Seeder;

class MatriculaTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Matricula::insert([
            [
                'prontuario' => 'CV1100785',
                'idAluno' => 1,
                'codigo_curso' => 'CPV18047',
                'previsao_conclusao' => '2011',
                'ano_ingresso' => '2008',
                'forma_ingresso' => 'Processo Seletivo Simplificado',
                'situacao_curso' => 'Matriculado'
            ],
            [
                'prontuario' => 'CV300158X',
                'idAluno' => 1,
                'codigo_curso' => 'CPV18600',
                'previsao_conclusao' => '2013',
                'ano_ingresso' => '2016',
                'forma_ingresso' => 'Ampla Concorrência (Vestibular)',
                'situacao_curso' => 'Evasão'
            ],
            [
                'prontuario' => 'CV1200984',
                'idAluno' => 2,
                'codigo_curso' => 'CPV18600',
                'previsao_conclusao' => '2013',
                'ano_ingresso' => '2016',
                'forma_ingresso' => 'Ampla Concorrência (Vestibular)',
                'situacao_curso' => 'Matriculado'
            ],
            [
                'prontuario' => 'CV1400321',
                'idAluno' => 3,
                'codigo_curso' => 'CPV18075',
                'previsao_conclusao' => '2011',
                'ano_ingresso' => '2014',
                'forma_ingresso' => 'Matrícula Direta (Inativa)',
                'situacao_curso' => 'Matriculado'
            ],
            [
                'prontuario' => 'CV1100416',
                'idAluno' => 4,
                'codigo_curso' => 'CPV18201',
                'previsao_conclusao' => '2014',
                'ano_ingresso' => '2018',
                'forma_ingresso' => 'Processo Seletivo Simplificado',
                'situacao_curso' => 'Matriculado'
            ],
        ]);
    }
}