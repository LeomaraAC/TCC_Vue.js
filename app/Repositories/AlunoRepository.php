<?php

namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Support\Facades\DB;
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

    public function selectAll() {
        return $this->model->select('cpf', 'nome')->orderBy('nome', 'asc')->get();
    }

    public function getAlunoAgendamento($idAgendamento) {
        return $this->model
            ->select(
                    'matricula.prontuario',
                    'alunos.nome', 
                    'alunos.responsavel',
                    'cursos.descricao as curso',
                    DB::raw('group_concat(telefone.numero) as telefone'))
            ->join('telefone', 'telefone.cpf','=', 'alunos.cpf')
            ->join('matricula', 'matricula.cpf','=', 'alunos.cpf')
            ->join('cursos', 'cursos.codigo','=', 'matricula.codigo_curso')
            ->join('agendamento_matricula', 'agendamento_matricula.prontuario','=', 'matricula.prontuario')
            ->join('agendamento', 'agendamento.idAgendamento','=', 'agendamento_matricula.idAgendamento')
            ->where('agendamento.idAgendamento', '=', $idAgendamento)
            ->where('telefone.deleted_at', '=', null)
            ->groupBy('matricula.prontuario')
            ->get();
        
    }
}