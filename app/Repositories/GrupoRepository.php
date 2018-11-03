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
    public function createGrupo(Request $dados) {
        $this->valida($dados);
        $permissoes = explode(',', $dados->idTelas);
        $grupo = $this->create(['nomeGrupo'=>$dados->grupo]);
        $grupo->funcoes()->attach(array_unique($permissoes));
    }

    /**
     * Update de um determinado grupo
     * @param  array  $dados
     * @param int $id
     */
    public function updateGrupo(Request $dados, $id) : bool {
        $this->valida($dados,$id);
        $permissoes = explode(',', $dados->idTelas);
        $update =  $this->update(['nomeGrupo'=>$dados->grupo], $id);
        $this->model->funcoes()->sync(array_unique($permissoes));
        return $update;
    }

    private function valida ($request, $id = null) {
        if ($id == null) {
            $request->validate([
                'grupo' =>'bail|required|unique:grupo,nomeGrupo|min:3|max:60|regex:/^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$/',
                'idTelas' =>'required'
            ]);
        } else {
            $request->validate([
                'grupo' =>'bail|required|min:3|max:60|regex:/^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$/|unique:grupo,nomeGrupo,'.$id.',idGrupo',
                'idTelas' =>'required'
            ]);
        }
    }
}