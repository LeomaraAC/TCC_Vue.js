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
        if(Gate::denies('aluno'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Alunos", "url" =>""]
        ]);
        $columns = json_encode($this->getColunas());
        return view('alunos.indexAlunos', compact('breadcrumb', 'columns'));
    }

    private function getColunas() {
        $columns = array();
        if (Gate::allows('visualizar_Aluno'))
            array_push($columns,["field"=>"pdf", "label" =>'', "width"=> '30px', "sortable"=>false]);
        
        array_push($columns,["field"=>"cpf", "label" =>"CPF"]);
        array_push($columns,["field"=>"nome", "label" =>"Aluno"]);
        array_push($columns,["field"=>"email_pessoal", "label" =>"Email Pessoal"]);
        return $columns;
    }

    

    /**
     * Display the specified resource.
     *
     * @param  \App\Aluno  $aluno
     * @return \Illuminate\Http\Response
     */
    public function show($cpf)
    {
        if(Gate::denies('visualizar_Aluno'))
            return redirect()->back()->with('error', 'Ops! Acesso negado');
        
        $matriculas = $this->repoMatricula->findByCpf($cpf);
        $aluno = $this->repository->find($cpf);
        $titulo = 'aluno_'.$aluno->cpf.'.pdf';
        return PDF::loadView('alunos.aluno_pdf', compact('aluno','matriculas'))->stream($titulo);
    }

    public function filtro($campo = 'nome',$order = 'asc', $filter = null){
        return $this->repository->filtro($campo,$order, $filter);
    }
}
