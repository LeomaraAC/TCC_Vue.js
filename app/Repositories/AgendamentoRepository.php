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

        $data = $this->dataFormatY_M_D($request->dataPrevista);
        $horaInicial =  $this->horaFormat($request->horaPrevistaInicio);
        $horaFinal =  $this->horaFormat($request->horaPrevistaFim);

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
        return array(
            'response' => $agendamento,
            'success' => true
        );
    }

    private function validaHorario($horaInicial, $horaFinal, $data) {
       
        $atendimentos = $this->model
                             ->where('dataPrevisto', '=', $data)
                             ->where('status', 'Agendada')
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

    public function atualizaStatusAtrasadas() {
        $dataAtual =  Carbon::now(new \DateTimeZone('America/Sao_Paulo'))->format('Y-m-d');
        return $this->model
                ->where('dataPrevisto', '<', $dataAtual)
                ->where('status', '=', 'Agendada')
                ->update(array('status' => 'Atrasada'));
    }
    
    public function atualizaStatusRealizada($id) {
        return $this->model
                ->where('idAgendamento', $id)
                ->update(array('status' => 'Realizada'));
    }

    public function getReunioes($status) {
        $order = 'asc';
        if($status == 'Atrasada'){
            $this->atualizaStatusAtrasadas();
            $order = 'desc';
        }
        $reunioes = $this->model
                    ->select('idAgendamento', 'dataPrevisto', 'horaPrevistaInicio', 'descricao', 'responsavel')
                    ->join('tipo_atendimento', 'tipo_atendimento.idTipo_atendimento','=', 'agendamento.idTipo_atendimento')
                    ->where('status', '=', $status)
                    ->where('status', '!=', 'Remarcada')        
                    ->where(function($q) {
                        $q->where('idUser', '=', Auth::user()->idUser)
                          ->orWhere('responsavel', '=', 'Setor');
                    })
                    ->orderBy('dataPrevisto', $order)
                    ->orderBy('horaPrevistaInicio', $order)
                    ->limit(5)
                    ->get();
        foreach($reunioes as $reuniao) {
            
            $reuniao->dataPrevisto = $this->dataFormatD_M_Y($reuniao->dataPrevisto);
        }
        return $reunioes;
    }

    
    private function dataFormatD_M_Y($data){
        $arrayData = explode("-", $data);
        $dia = $arrayData[2];
        $mes = $arrayData[1];
        $ano = $arrayData[0];

        return Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('d/m/Y');
    }
    
    private function dataFormatY_M_D($data){
        $arrayData = explode("/", $data);
        $dia = $arrayData[0];
        $mes = $arrayData[1];
        $ano = $arrayData[2];

        return Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('Y-m-d');
    }

    private function horaFormat($horaFormatar){
        $horario = explode(":", $horaFormatar);
        $hora = $horario[0];
        $minuto = $horario[1];
        return Carbon::createFromTime($hora, $minuto, '00', 'America/Sao_Paulo')->format('H:i:s');
    }

    public function showAgendamento($id) {
        $agendamento = $this->model
            ->select(
                    'idAgendamento',
                    'dataPrevisto as data', 
                    'horaPrevistaInicio as horaInicio',
                    "horaPrevistaFim AS horaFim",
                    'descricao',
                    'formaAtendimento',
                    'status',
                    'responsavel')
            ->join('tipo_atendimento', 'tipo_atendimento.idTipo_atendimento','=', 'agendamento.idTipo_atendimento')
            ->where('idAgendamento', '=', $id)
            ->where('status', '!=', 'Remarcada')        
            ->where(function($q) {
                $q->where('idUser', '=', Auth::user()->idUser)
                    ->orWhere('responsavel', '=', 'Setor');
            })
            ->first();
            if($agendamento)
                $agendamento->data = $this->dataFormatD_M_Y($agendamento->data);
        return $agendamento;
    }

    public function filtro($orderBy = 'idAgendamento',$sortBy = 'asc', $filter = null, $responsavel = null) {
        $this->atualizaStatusAtrasadas();
        $reunioes = $this->model
        ->select('idAgendamento',
                 'dataPrevisto',
                 'horaPrevistaInicio',
                 'descricao as tipo',
                 'responsavel',
                 'formaAtendimento',
                 'status')
        ->join('tipo_atendimento', 'tipo_atendimento.idTipo_atendimento','=', 'agendamento.idTipo_atendimento')
        ->where('status', '!=', 'Remarcada')        
        ->where(function($q) use($filter) {
            $q->where('status', 'like', '%'.$filter.'%')
              ->orWhere('dataPrevisto', 'like', '%'.$filter.'%')
              ->orWhere('horaPrevistaInicio', 'like', '%'.$filter.'%')
              ->orWhere('descricao', 'like', '%'.$filter.'%')
              ->orWhere('responsavel', 'like', '%'.$filter.'%')
              ->orWhere('formaAtendimento', 'like', '%'.$filter.'%');
        })
        ->when($responsavel == 'setor', function($q){
                return $q->where('responsavel', '=', 'Setor');
        }, function($q) use($responsavel){
            return $q->when($responsavel == 'particular', function($query) {
                    return $query->where('idUser', '=', Auth::user()->idUser)
                    ->where('responsavel', '=', 'Particular');
            }, function($query) {
                return $query->where(function($queryWhere){
                    $queryWhere->where('idUser', '=', Auth::user()->idUser)
                    ->orWhere('responsavel', '=', 'Setor');
                });
            });
        })
        ->orderBy($orderBy, $sortBy)
        ->paginate(25);
        foreach ($reunioes as $reuniao) {
            $arrayData = explode("-", $reuniao->dataPrevisto);
            $dia = $arrayData[2];
            $mes = $arrayData[1];
            $ano = $arrayData[0];

            $reuniao->dataPrevisto = Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('d/m/Y');
        }
        return $reunioes;
    }

    public function cancelarAtendimento($id) {
        $this->model = $this->findOrFail($id);
        return $this->model->update(array('status' => 'Cancelada'));
    }

    public function findReagendamento($id) {
        $agendamento = $this->model
                            ->select('idAgendamento','dataPrevisto', 'horaPrevistaInicio', 'horaPrevistaFim', 'status')
                            ->where('idAgendamento', $id)
                            ->where('status', '!=', 'Remarcada')        
                            ->where(function($q) {
                                $q->where('idUser', '=', Auth::user()->idUser)
                                  ->orWhere('responsavel', '=', 'Setor');
                            })
                            ->first();
        if($agendamento) {
            $arrayData = explode("-", $agendamento->dataPrevisto);
            $dia = $arrayData[2];
            $mes = $arrayData[1];
            $ano = $arrayData[0];
            $agendamento->dataPrevisto = Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('d/m/Y');
            
            $horario = explode(":", $agendamento->horaPrevistaInicio);
            $agendamento->horaPrevistaInicio = Carbon::createFromTime($horario[0], $horario[1], $horario[2], 'America/Sao_Paulo')->format('H:i');
            
            $horario = explode(":", $agendamento->horaPrevistaFim);
            $agendamento->horaPrevistaFim = Carbon::createFromTime($horario[0], $horario[1], $horario[2], 'America/Sao_Paulo')->format('H:i');
        }
        return $agendamento;
    }

    public function remarcar(Request $request, $id) {
        $request->validate([
            'horaInicial' => 'required|date_format:"H:i"',
            'horaFinal' => 'required|date_format:"H:i"',
            'data' => 'required|date_format:"d/m/Y"'
        ]);
        
        $data = $this->dataFormatY_M_D($request->data);
        $horaInicial =  $this->horaFormat($request->horaInicial);
        $horaFinal =  $this->horaFormat($request->horaFinal);

        if($horaFinal < $horaInicial)
            return array(
                'response' => 'O termino da reunião deve ser posterior ao seu início!',
                'success' => false
            ); 
        
        if(!$this->validaHorario($horaInicial, $horaFinal, $data))
            return array(
                'response' => 'A hora agendada para reunião entra em conflito com outra reunião!',
                'success' => false
            );  
        
        $update = $this->update([
            'dataRemarcada' => $data,
            'status' => 'Remarcada'
        ], $id);

        $remarcar = $this->find($id);

        $agendamento = $this->create([
            'dataPrevisto' => $data,
            'horaPrevistaInicio' => $horaInicial,
            'horaPrevistaFim' => $horaFinal,
            'formaAtendimento' => $remarcar->formaAtendimento,
            'responsavel' => $remarcar->responsavel,
            'status' => 'Agendada',
            'idTipo_atendimento' => $remarcar->idTipo_atendimento,
            'idUser' => Auth::user()->idUser
        ]);

        if($agendamento && $update)
            return array(
                'response' => 'Agendamento remarcado com sucesso!',
                'success' => true
            ); 
        else
            return array(
                'response' => 'Ops! Ocorreu um erro ao tentar reagendar o atendimento.',
                'success' => false
            );
    }
}