<?php
namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Curso;

class CursoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Curso $curso) {
        $this->model = $curso;
    }
}