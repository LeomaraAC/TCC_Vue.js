<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Repositories\Contracts\Base\BaseRepository;
use App\Grupo;


class GrupoRepository extends  BaseRepository
{

    protected $model;

    public function __construct(Grupo $grupo) {
        $this->model = $grupo;
    }

    /**
     * Criar um novo grupo, ligando-o com as suas permissoes
     * @param  array  $dados
     */
    public function createGrupo(array $dados) {
        $permissoes = explode(',', $dados['idTelas']);
        $grupo = $this->create(['nomeGrupo'=>$dados['grupo']]);
        $grupo->funcoes()->attach(array_unique($permissoes));
    }

    /**
     * Update de um determinado grupo
     * @param  array  $dados
     * @param int $id
     */
    public function updateGrupo(array $dados, $id) : bool {
        $permissoes = explode(',', $dados['idTelas']);
        $update =  $this->update(['nomeGrupo'=>$dados['grupo']], $id);
        $this->model->funcoes()->sync(array_unique($permissoes));
        return $update;
    }
}