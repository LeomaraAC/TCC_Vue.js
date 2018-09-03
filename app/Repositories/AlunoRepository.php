<?php

namespace App\ Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Http\Request;
use App\Aluno;


class AlunoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Aluno $aluno) {
        $this->model = $aluno;
    }
    public function filtro($orderBy = 'idAluno',$sortBy = 'asc', $filter = null){
        return $this->model->join('cursos','alunos.idCurso', '=', 'cursos.idCurso')
                            ->orderBy($orderBy, $sortBy)
                            ->where('nome', 'like', '%'.$filter.'%')
                            ->orWhere('prontuario', 'like', '%'.$filter.'%')
                            ->orWhere('email', 'like', '%'.$filter.'%')
                            ->select('alunos.idAluno','alunos.nome', 'alunos.prontuario', 'alunos.email', 'cursos.sigla')
                            ->paginate(5);
    }

    public function inserirAluno(Request $request) {
        $this->validaInserir($request);
        return $this->create([
            'prontuario' => $request->prontuario,
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'semestreAno' => $request->semestre,
            'statusMatricula' => 'ativo',
            'Observacoes' => $request->observacao,
            'idCurso' => $request->curso
        ]);
    }
        
    public function atualizarAluno(Request $request, $id) {
        $this->validaEditar($request, $id);
        return $this->update([
            'prontuario' => $request->prontuario,
            'nome' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'semestreAno' => $request->semestre,
            'statusMatricula' => $request->statusMatricula,
            'Observacoes' => $request->observacao,
            'idCurso' => $request->curso
        ], $id);
    }
    private function validaInserir(Request $request)
    {
        $request->validate([
            'prontuario' => 'required|alpha_num|max:10|min:5|unique:alunos,prontuario',
            'nome' => 'required|string|max:60|min:3',
            'email' => 'required|string|email|max:60|unique:alunos,email',
            'telefone' => 'string|max:20|min:7',
            'semestre' => 'required|numeric',
            'observacao' => 'string|max:300',
            'curso'=>'required'
        ]);
    }
   
    private function validaEditar(Request $request, $id)
    {
        $request->validate([
            'prontuario' => 'required|alpha_num|max:10|min:5|unique:alunos,prontuario,'.$id.',idAluno',
            'nome' => 'required|string|max:60|min:3',
            'email' => 'required|string|email|max:60|unique:alunos,email,'.$id.',idAluno',
            'telefone' => 'string|max:20|min:7',
            'semestre' => 'required|numeric',
            'observacao' => 'string|max:300',
            'curso'=>'required'
        ]);
    }
}