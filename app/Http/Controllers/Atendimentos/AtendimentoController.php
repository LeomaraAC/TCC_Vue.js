<?php

namespace App\Http\Controllers\Atendimentos;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Repositories\AgendamentoRepository;
use App\Repositories\AtendimentoRepository;
use App\Repositories\UserRepository;
use App\RegistroAtendimento;
use Auth;

class AtendimentoController extends Controller
{
    public function __construct(AgendamentoRepository $repoAgendamento,
                                UserRepository $repoUsuario,
                                AtendimentoRepository $repoAtendimento) {
        $this->repoAgendamento = $repoAgendamento;
        $this->repoUsuario = $repoUsuario;
        $this->repoAtendimento = $repoAtendimento;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('agendamento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        // $columns = json_encode($this->getColunas());
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Atendimentos", "url" =>""]
        ]);
        return view('atendimentos.AtendimentosRealizados.indexAtendimento', compact('breadcrumb'));
    }

    // private function getColunas() {
    //     $columns = array(["field"=>"idAgendamento", "hidden" =>true]);
    //     if (Gate::allows('agendamento'))
    //         array_push($columns,["field"=>"visualizar", "label" =>'', "width"=> '30px', "sortable"=>false]);
    //     if (Gate::allows('cancelar_agendamento'))
    //         array_push($columns,["field"=>"cancelar", "label" =>'', "width"=> '30px', "sortable"=>false]);
    //     if (Gate::allows('remarcar_agendamento'))
    //         array_push($columns,["field"=>"remarcar", "label" =>'', "width"=> '30px', "sortable"=>false]);
    //     if (Gate::allows('registrar_agendamento'))
    //         array_push($columns,["field"=>"registrar", "label" =>'', "width"=> '30px', "sortable"=>false]);
    //     array_push($columns,["field"=>"dataPrevisto", "label" =>"Data"]);
    //     array_push($columns,["field"=>"horaPrevistaInicio", "label" =>"Hora"]);
    //     array_push($columns,["field"=>"tipo", "label" =>"Tipo"]);
    //     array_push($columns,["field"=>"responsavel", "label" =>"Responsabilidade"]);
    //     array_push($columns,["field"=>"formaAtendimento", "label" =>"Forma de atendimento"]);
    //     array_push($columns,["field"=>"status", "label" =>"Status"]);
    //     return $columns;
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function formRegistrar($id) {
        if(Gate::denies('registrar_agendamento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Agendamentos", "url" =>route('agendamento.index')],
            ["titulo"=>"Registrar atendimento", "url" =>""]
        ]);
        $agendamento = $this->repoAgendamento->getAgendamentoForRegistro($id);
        $user = $this->repoUsuario->all();
        $userAtual = Auth::user();
        if($agendamento)
            return view('atendimentos.agendamento.agendamento_registrar', compact('breadcrumb', 'agendamento', 'user', 'userAtual'));
        else 
            return redirect()->route('agendamento.index')->with('error', 'Ops! O atendimento a ser registrado não foi encontrado.');
    }
    public function registrarAtendimento (Request $request) {
        if(Gate::denies('registrar_agendamento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        $atendimento = $this->repoAtendimento->salvarResgistro($request);
        if($atendimento) {
            $atualizaAgendamento = $this->repoAgendamento->atualizaStatusRealizada($request->idAgendamento);
            if($atualizaAgendamento){
                $request->session()->flash('success', 'Agendamento registrado com sucesso!');
                return response()->json('sucesso');
            }
        }
        return response()->json('Não foi possível registrar essa reunião!', 422);
    }
}
