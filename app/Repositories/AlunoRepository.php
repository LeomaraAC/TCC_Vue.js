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
        return $this->model->orderBy($orderBy, $sortBy)
                            ->where('cpf', 'like', '%'.$filter.'%')
                            ->orWhere('nome', 'like', '%'.$filter.'%')
                            ->orWhere('email_pessoal', 'like', '%'.$filter.'%')
                            ->paginate(25);
    }
}