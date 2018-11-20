<?php

use Illuminate\Database\Seeder;

class AgendamentosTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Agendamento::insert([
            [
                'idTipo_atendimento' => 1,
                'idUser' => 1,
                'dataPrevisto' => '2020-11-06',
                'horaPrevistaInicio' => '17:00:00',
                'horaPrevistaFim' => '17:15:00',
                'formaAtendimento' => 'Individual',
                'responsavel' => 'Particular',
                'status' => 'Agendada',
            ],
            [
                'idTipo_atendimento' => 2,
                'idUser' => 2,
                'dataPrevisto' => '2020-11-06',
                'horaPrevistaInicio' => '17:30:00',
                'horaPrevistaFim' => '18:30:00',
                'formaAtendimento' => 'Familiar',
                'responsavel' => 'Setor',
                'status' => 'Agendada',
            ],
            [
                'idTipo_atendimento' => 3,
                'idUser' => 1,
                'dataPrevisto' => '2020-11-06',
                'horaPrevistaInicio' => '19:00:00',
                'horaPrevistaFim' => '19:20:00',
                'formaAtendimento' => 'Grupo',
                'responsavel' => 'Setor',
                'status' => 'Agendada',
            ],
            [
                'idTipo_atendimento' => 3,
                'idUser' => 1,
                'dataPrevisto' => '2020-11-06',
                'horaPrevistaInicio' => '19:20:00',
                'horaPrevistaFim' => '19:35:00',
                'formaAtendimento' => 'Familiar',
                'responsavel' => 'Setor',
                'status' => 'Agendada',
            ],
            [
                'idTipo_atendimento' => 3,
                'idUser' => 1,
                'dataPrevisto' => '2018-11-06',
                'horaPrevistaInicio' => '17:30:00',
                'horaPrevistaFim' => '18:30:00',
                'formaAtendimento' => 'Individual',
                'responsavel' => 'Particular',
                'status' => 'Cancelada',
            ]
        ]);

        $agendamentos = App\Agendamento::all();
        $agendamentos->each(function($a){
            $num = rand(1,4);
            $aluno = App\Matricula::where('idAluno', $num)->first();
            $a->matricula()->attach($aluno);
        });
    }
}
