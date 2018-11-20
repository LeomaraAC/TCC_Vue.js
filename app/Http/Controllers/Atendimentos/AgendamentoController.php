<?php

namespace App\Http\Controllers\Atendimentos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Repositories\AlunoRepository;
use App\Repositories\MatriculaRepository;
use App\Repositories\AgendamentoRepository;
use App\Repositories\TipoAtendimentoRepository;

class AgendamentoController extends Controller
{
    public function __construct(AlunoRepository $repoAluno, 
                                AgendamentoRepository $repoAgendamento,
                                TipoAtendimentoRepository $repoTipo,
                                MatriculaRepository $repoMatricula) {
        $this->repoAlunos = $repoAluno;
        $this->repoAgendamento = $repoAgendamento;
        $this->repoTipo = $repoTipo; 
        $this->repoMatricula = $repoMatricula; 
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
        
        $columns = json_encode($this->getColunas());
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Agendamentos", "url" =>""]
        ]);
        return view('atendimentos.agendamento.indexAgendamento', compact('breadcrumb', 'columns'));
    }

    private function getColunas() {
        $columns = array(["field"=>"idAgendamento", "hidden" =>true]);
        if (Gate::allows('agendamento'))
            array_push($columns,["field"=>"visualizar", "label" =>'', "width"=> '30px', "sortable"=>false]);
        if (Gate::allows('cancelar_agendamento'))
            array_push($columns,["field"=>"cancelar", "label" =>'', "width"=> '30px', "sortable"=>false]);
        if (Gate::allows('remarcar_agendamento'))
            array_push($columns,["field"=>"remarcar", "label" =>'', "width"=> '30px', "sortable"=>false]);
        if (Gate::allows('registrar_agendamento'))
            array_push($columns,["field"=>"registrar", "label" =>'', "width"=> '30px', "sortable"=>false]);
        array_push($columns,["field"=>"dataPrevisto", "label" =>"Data"]);
        array_push($columns,["field"=>"horaPrevistaInicio", "label" =>"Hora"]);
        array_push($columns,["field"=>"tipo", "label" =>"Tipo"]);
        array_push($columns,["field"=>"responsavel", "label" =>"Responsabilidade"]);
        array_push($columns,["field"=>"formaAtendimento", "label" =>"Forma de atendimento"]);
        array_push($columns,["field"=>"status", "label" =>"Status"]);
        return $columns;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('agendar_Atendimento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Agendamentos", "url" =>route('agendamento.index')],
            ["titulo"=>"Agendar atendimento", "url" =>""]
        ]);
        $alunos = $this->repoAlunos->selectAll();
        $tipos = $this->repoTipo->all();
        return view('atendimentos.agendamento.agendamento_create', compact('breadcrumb', 'alunos', 'tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('agendar_Atendimento'))
            return response()->json(['authorization' => ['Ops! Acesso negado.']], 401);
        $create = $this->repoAgendamento->createAgendamento($request);
        if($create['success']){

            foreach($request->alunos as $a) {
                $m = $this->repoMatricula->find($a["prontuario"]);
                $m->turma = $a["semestre"];
                $m->save();
            }
            return response()->json($create['response'], 200);
        }
        else
            return response()->json($create['response'], 422);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies('agendamento'))
            return response()->json('Ops! Acesso negado.', 401);        
        $atendimento = $this->repoAgendamento->showAgendamento($id);
        $alunos = $this->repoAlunos->getAlunoAgendamento($id);
        
        if($atendimento && $alunos){
            return response()->json([
                'agendamento' => $atendimento,
                'alunos' => $alunos
            ], 200);
        }
        else
            return response()->json('Atendimento não encontrado!', 404);
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
        
    public function filtro($campo = 'dataPrevisto',$order = 'asc', $responsavel='todos', $filter = null){
        $responsavel = $responsavel == 'todos' ? null: $responsavel; 
        return $this->repoAgendamento->filtro($campo,$order, $filter, $responsavel);
    }
}
