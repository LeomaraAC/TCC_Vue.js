<?php

namespace App\Http\Controllers;

use App\Curso;
use Illuminate\Http\Request;
use App\Repositories\CursoRepository;

class CursoController extends Controller
{
    protected $repository;

    public function __construct(CursoRepository $repository) {
        $this->repository = $repository;
    }
    public function getCursos() {
        return $this->repository->allOrder('descricao');
    }
}
