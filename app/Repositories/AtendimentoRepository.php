<?php

namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\RegistroAtendimento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Auth;


class AtendimentoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(RegistroAtendimento $registro) {
        $this->model = $registro;
    }

    public function salvarResgistro(Request $request) {
        $request->validate([
            'idAgendamento' => 'bail|required|exists:agendamento,idAgendamento',
            'data' => 'bail|required|date_format:"d/m/Y"',
            'hora' => 'bail|required|date_format:"H:i"',
            'familia' => 'bail|required',
            'responsaveis.*.idUser'=>'bail|required|exists:users,idUser',
            'resumo'=>'bail|required|string'
        ]);
        $request->data = $this->dataFormatY_M_D($request->data);
        $request->hora = $this->horaFormat($request->hora);
        $atendimento = $this->create([
            'dataRealizado' => $request->data,
            'horaRealizado' => $request->hora,
            'comparecimentoFamiliar' => $request->familia,
            'grauParentesco' => $request->parentesco,
            'resumo' => Crypt::encrypt($request->resumo),
            'idAgendamento' => $request->idAgendamento
        ]);

        foreach ($request->responsaveis as $responsavel) {
            $atendimento->user()->attach($responsavel['idUser']); 
        }
        // foreach ($a->user as $product)
        // {
        //     echo $product->pivot->resumo;
        // }

        return $atendimento;
    } 

    private function dataFormatY_M_D($data){
        $arrayData = explode("/", $data);
        $dia = $arrayData[0];
        $mes = $arrayData[1];
        $ano = $arrayData[2];

        return Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('Y-m-d');
    }
    private function dataFormatD_M_A($data) {
        $arrayData = explode("-", $data);
        $dia = $arrayData[2];
        $mes = $arrayData[1];
        $ano = $arrayData[0];

        return Carbon::createFromDate($ano, $mes, $dia, 'America/Sao_Paulo')->format('d/m/Y');
    }
    private function horaFormat($horaFormatar){
        $horario = explode(":", $horaFormatar);
        $hora = $horario[0];
        $minuto = $horario[1];
        return Carbon::createFromTime($hora, $minuto, '00', 'America/Sao_Paulo')->format('H:i:s');
    }

    public function filtro($orderBy = 'idAgendamento',$sortBy = 'asc', $filter = null)  {
        $atendimentos = $this->model
            ->select(
                    'registro_atendimento.idRegistro',
                    'dataRealizado', 
                    'horaRealizado',
                    'formaAtendimento',
                    'grauParentesco as responsaveis')
            ->join('agendamento', 'agendamento.idAgendamento', '=', 'registro_atendimento.idAgendamento')
            ->join('registro_user', 'registro_atendimento.idRegistro','=', 'registro_user.idRegistro')
            ->join('users', 'registro_user.idUser','=', 'users.idUser')
            ->where(function($q) use($filter) {
                $q->where('dataRealizado', 'like', '%'.$filter.'%')
                  ->orWhere('horaRealizado', 'like', '%'.$filter.'%')
                  ->orWhere('formaAtendimento', 'like', '%'.$filter.'%')
                  ->orWhere('users.nome', 'like', '%'.$filter.'%');
            })
            ->where(function($q) {
                $q->where('registro_user.idUser', '=', Auth::user()->idUser)
                    ->orWhere('agendamento.responsavel', '=', 'Setor');
            }) 
            ->orderBy($orderBy, $sortBy)
            ->groupBy('registro_user.idRegistro')
            ->paginate(25);
            foreach ($atendimentos as $a) {
                $r = $this->model
                        ->select(DB::raw('group_concat(nome) as responsaveis'))
                        ->join('registro_user', 'registro_atendimento.idRegistro','=', 'registro_user.idRegistro')
                        ->join('users', 'registro_user.idUser','=', 'users.idUser')
                        ->where('registro_user.idRegistro', $a->idRegistro)
                        ->groupBy('registro_user.idRegistro')
                        ->first();
                $a->responsaveis = $r->responsaveis;
                $a->dataRealizado = $this->dataFormatD_M_A($a->dataRealizado);
            }
            return $atendimentos;
    }

}