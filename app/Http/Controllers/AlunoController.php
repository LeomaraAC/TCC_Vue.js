<?php

namespace App\Http\Controllers;

use App\Aluno;
use Illuminate\Http\Request;
use App\Repositories\AlunoRepository;
use App\Repositories\MatriculaRepository;
use Illuminate\Support\Facades\Gate;
use PDF;

class AlunoController extends Controller
{

    protected $repository;
    protected $repoMatricula;

    public function __construct(AlunoRepository $repository, MatriculaRepository $repoMatricula) {
        $this->repository = $repository;
        $this->repoMatricula = $repoMatricula;
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
        array_push($columns,["field"=>"statusMatricula",  "hidden" =>true]);
        if (Gate::allows('editar_Aluno'))
            array_push($columns,["field"=>"editar", "label" =>'', "width"=> '50px', "sortable"=>false]);
        
        if (Gate::allows('tran_dest_curso_Aluno'))
            array_push($columns,["field"=>"trancar_destrancar", "label" =>'', "width"=> '50px', "sortable"=>false]);
        
        if (Gate::allows('finalizar_curso_Aluno'))
            array_push($columns,["field"=>"finalizar", "label" =>'', "width"=> '50px', "sortable"=>false]);
        
        array_push($columns,["field"=>"nome", "label" =>"Usuário"]);
        array_push($columns,["field"=>"prontuario", "label" =>"Prontuário"]);
        array_push($columns,["field"=>"email", "label" =>"Email"]);
        array_push($columns,["field"=>"sigla", "label" =>"Curso"]);
        array_push($columns,["field"=>"statusMatricula", "label" =>"Status"]);
        return $columns;
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

    {
        return 'destroy';
    }

    public function filtro($campo = 'idAluno',$order = 'asc', $filter = null){
        return $this->repository->filtro($campo,$order, $filter);
    }
}
