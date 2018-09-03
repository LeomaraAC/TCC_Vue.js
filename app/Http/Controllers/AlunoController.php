<?php

namespace App\Http\Controllers;

use App\Aluno;
use Illuminate\Http\Request;
use App\Repositories\AlunoRepository;
use Illuminate\Support\Facades\Gate;

class AlunoController extends Controller
{

    protected $repository;

    public function __construct(AlunoRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Alunos", "url" =>""]
        ]);
        $columns = json_encode($this->getColunas());
        return view('alunos.indexAlunos', compact('breadcrumb', 'columns'));
    }

    private function getColunas() {
        $columns = array(["field"=>"idAluno", "hidden" =>true]);
        if (Gate::allows('excluir_Aluno'))
            array_push($columns,["field"=>"deletar", "label" =>'', "width"=> '50px', "sortable"=>false]);

        if (Gate::allows('editar_Aluno'))
            array_push($columns,["field"=>"editar", "label" =>'', "width"=> '50px', "sortable"=>false]);
            
        array_push($columns,["field"=>"nome", "label" =>"Usuário"]);
        array_push($columns,["field"=>"prontuario", "label" =>"Prontuário"]);
        array_push($columns,["field"=>"email", "label" =>"Email"]);
        array_push($columns,["field"=>"sigla", "label" =>"Curso"]);
        return $columns;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('incluir_Aluno'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Alunos", "url" =>route('alunos.index')],
            ['titulo'=>'Criar Aluno', 'url' => '']
        ]);
        return view('alunos.alunos_create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('incluir_Aluno'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        $this->repository->inserirAluno($request);
        return redirect()->route('alunos.index')->with('success', 'Aluno cadastrado com sucesso!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function show(Aluno $aluno)
    {
        return 'show';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function edit(Aluno $aluno)
    {
        return 'edit';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Aluno $aluno)
    {
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aluno $aluno)
    {
        return 'destroy';
    }

    public function filtro($campo = 'idAluno',$order = 'asc', $filter = null){
        return $this->repository->filtro($campo,$order, $filter);
    }
}
