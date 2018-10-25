<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\MatriculaRepository;

class MatriculaController extends Controller
{
    public function __construct(MatriculaRepository $repoMatricula) {
        $this->repoMatricula = $repoMatricula;
    }
    public function getMatricula($cpf) {
        $matricula = $this->repoMatricula->findByCpf($cpf);
        $cursos = array();
        foreach ($matricula as $m) {
            $curso = [
                "prontuario" => $m->prontuario,
                "codigo" => $m->codigo_curso,
                "descricao" =>$m->curso->descricao
            ];
            array_push($cursos,$curso);
        }
        return $cursos;
    }
}
