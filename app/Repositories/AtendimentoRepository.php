<?php

namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Http\Request;
use App\RegistroAtendimento;
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
            'responsaveis.*.comentario'=>'bail|required|string'
        ]);
        $request->data = $this->dataFormatY_M_D($request->data);
        $request->hora = $this->horaFormat($request->hora);
        $atendimento = $this->create([
            'dataRealizado' => $request->data,
            'horaRealizado' => $request->hora,
            'comparecimentoFamiliar' => $request->familia,
            'grauParentesco' => $request->parentesco,
            'idAgendamento' => $request->idAgendamento
        ]);

        foreach ($request->responsaveis as $responsavel) {
            $atendimento->user()->attach($responsavel['idUser'], ['resumo' => $responsavel['comentario']]); 
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
    private function horaFormat($horaFormatar){
        $horario = explode(":", $horaFormatar);
        $hora = $horario[0];
        $minuto = $horario[1];
        return Carbon::createFromTime($hora, $minuto, '00', 'America/Sao_Paulo')->format('H:i:s');
    }
}