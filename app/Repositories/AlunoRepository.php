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
}