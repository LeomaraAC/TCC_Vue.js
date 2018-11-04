<?php
namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Matricula;

class MatriculaRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Matricula $matricula) {
        $this->model = $matricula;
    }

    public function findByIdAluno($idAluno) {
        return $this->model
                    ->orderBy('ano_ingresso', 'desc')
                    ->where('idAluno','=',$idAluno)
                    ->get();
    }

    public function findTurma($prontuario) {
        return $this->model->select('turma')->where('prontuario', '=', $prontuario)->get();
    }
}