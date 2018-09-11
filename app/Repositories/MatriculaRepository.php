<?php
namespace App\ Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Matricula;

class MatriculaRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Matricula $matricula) {
        $this->model = $matricula;
    }
}