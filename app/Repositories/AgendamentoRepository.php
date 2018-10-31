<?php
namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Http\Request;
use App\Agendamento;
use Carbon\Carbon;
use Validator;
use Auth;


class AgendamentoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Agendamento $agendamento) {
        $this->model = $agendamento;
    }

    
    public function createAgendamento(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'horaPrevistaInicio' => 'required|date_format:"H:i"',
            'horaPrevistaFim' => 'required|date_format:"H:i"',
            'dataPrevista' => 'required|date_format:"d/m/Y"',
            'alunos.*.prontuario' => 'required|exists:matricula,prontuario',
            'alunos.*.codigoCurso' => 'required|exists:cursos,codigo',
            'alunos.*.semestre' => 'required|numeric|min:1',
            'responsavel' => 'required|in:Particular,Setor',
            'atendimento' => 'required|in:Familiar,Individual,Grupo',
            'tipo' => 'required|exists:tipo_atendimento,idTipo_atendimento'

        ]);
    
        if($validator->fails()){
            return array(
                        'response' => $validator->messages(),
                        'success' => false
                    );
        } 
        
        $horario = explode(":", $request->horaPrevistaInicio);
        $horaInicio = $horario[0];
        $minutoInicio = $horario[1];

        $horario = explode(":", $request->horaPrevistaFim);
        $horaFim = $horario[0];
        $minutoFim = $horario[1];

        $arrayData = explode("/", $request->dataPrevista);
        $dia = $arrayData[0];
        $mes = $arrayData[1];
        $ano = $arrayData[2];

        $data = Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('Y-m-d');
        $horaInicial =  Carbon::createFromTime($horaInicio, $minutoInicio, '00', 'America/Sao_Paulo')->format('H:i:s');
        $horaFinal =  Carbon::createFromTime($horaFim, $minutoFim, '00', 'America/Sao_Paulo')->format('H:i:s');

        if($horaFinal < $horaInicial)
            return array(
                'response' => ['horario' => ['O termino da reunião deve ser posterior ao seu início!']],
                'success' => false
            ); 
        if(!$this->validaHorario($horaInicial, $horaFinal, $data))
            return array(
                'response' => ['horario' => ['A hora agendada para reunião entra em conflito com outra reunião!']],
                'success' => false
            );        
        $agendamento = $this->create([
            'dataPrevisto' => $data,
            'horaPrevistaInicio' => $horaInicial,
            'horaPrevistaFim' => $horaFinal,
            'formaAtendimento' => $request->atendimento,
            'responsavel' => $request->responsavel,
            'status' => 'Agendada',
            'idTipo_atendimento' => $request->tipo,
            'idUser' => Auth::user()->idUser
        ]);
        
        foreach($request->alunos as $aluno) {
            $agendamento->matricula()->attach($aluno["prontuario"]);
        }
        //  
        /*   
            alunos.*.prontuario -> tabela de relacionamento
            alunos.*.semestre -> tabela matricula
            */
        
        return array(
            'response' => $agendamento,
            'success' => true
        );
    }

    private function validaHorarioVisivel($horaInicial, $horaFinal, $data) {
       
        $atendimentos = $this->model
                             ->where('todos', '=', true)
                             ->where('dataPrevisto', '=', $data)
                             ->where(function($query) use ($horaFinal, $horaInicial) {
                                $query->where(function($q) use ($horaFinal, $horaInicial) {
                                   $q->where('horaPrevistaInicio', '>=', $horaInicial)
                                     ->where('horaPrevistaFim', '<=', $horaFinal); 
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '>=', $horaInicial)
                                      ->where('horaPrevistaInicio', '<', $horaFinal)
                                      ->where('horaPrevistaFim', '>=', $horaFinal);
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '<=', $horaInicial)
                                      ->where('horaPrevistaFim', '>', $horaInicial)
                                      ->where('horaPrevistaFim', '<=', $horaFinal);
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '<=', $horaInicial)
                                      ->where('horaPrevistaFim', '>=', $horaFinal);
                                });
                             })
                             ->count();
        if($atendimentos == 0)
            return true;
        else
            return false;
    }

    private function validaHorarioParticular($horaInicial, $horaFinal, $data) {
       
        $atendimentos = $this->model
                             ->where('todos', '=', false)
                             ->where('idUser', '=', Auth::user()->idUser)
                             ->where('dataPrevisto', '=', $data)
                             ->where(function($query) use ($horaFinal, $horaInicial) {
                                $query->where(function($q) use ($horaFinal, $horaInicial) {
                                   $q->where('horaPrevistaInicio', '>=', $horaInicial)
                                     ->where('horaPrevistaFim', '<=', $horaFinal); 
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '>=', $horaInicial)
                                      ->where('horaPrevistaInicio', '<', $horaFinal)
                                      ->where('horaPrevistaFim', '>=', $horaFinal);
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '<=', $horaInicial)
                                      ->where('horaPrevistaFim', '>', $horaInicial)
                                      ->where('horaPrevistaFim', '<=', $horaFinal);
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '<=', $horaInicial)
                                      ->where('horaPrevistaFim', '>=', $horaFinal);
                                });
                             })
                             ->count();
        if($atendimentos == 0)
            return true;
        else
            return false;
    }
}