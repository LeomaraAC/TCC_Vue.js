<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Repositories\AgendamentoRepository;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AgendamentoRepository $repoAgendamento)
    {
        $this->middleware('auth');
        $this->repoAgendamento = $repoAgendamento;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rowsProximas = $rowsAtrasadas = $columns = [];
        if(Gate::allows('agendamento')) {
            $rowsProximas = $this->getReunioesIndex('Agendada');
            $rowsAtrasadas = $this->getReunioesIndex('Atrasada');
            $columns = json_encode([
                ["field"=>"idAgendamento", "hidden" =>true],
                ["field"=>"visualizar", "label" =>'', "width"=> '30px', "sortable"=>false],
                ["field"=>"dataPrevisto", "label" =>'Data', "sortable"=>false],
                ["field"=>"horaPrevistaInicio", "label" =>'Hora', "sortable"=>false],
                ["field"=>"descricao", "label" =>'Tipo', "sortable"=>false],
                ["field"=>"responsavel", "label" =>'Responsável', "sortable"=>false],
            ]);
        }
        return view('home', compact('rowsProximas','rowsAtrasadas', 'columns'));
       
    }

    public function getReunioesIndex($status) {
        if(Gate::allows('agendamento'))
            return $this->repoAgendamento->getReunioes($status);
    }

    
}
