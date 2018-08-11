<?php
namespace App\ Repositories\Contracts\Base;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface {
    protected $model;

    /**
     * Cria um registro
     *  @param array $dados
     */
    public function create(array $dados) {
        return $this->model->create($dados);
    }

    /**
     * Atualiza um registro
     *  @param array $dados
     *  @param int $Id
     */
    public function update(array $dados, $id) : bool {
        $this->model = $this->findOrFail($id);
        return $this->model->update($dados);
    }

    /**
     * Retorna todos os dados, aplicando paginação, ordenação e filtro 
     * @param String $orderBy
     * @param String $sortBy
     * @param String $filterBy
     * @param String $filter
     * @param int $page
     */
    public function allPaginate($orderBy, $sortBy='asc',$filterBy, $filter=null, $page=5) {
        $sortBy = $sortBy == 'asc' || $sortBy == 'desc' ? $sortBy : 'asc';
        return $this->model->orderBy($orderBy, $sortBy)->where($filterBy, 'like', '%'.$filter.'%')->paginate($page);
    }

    /**
     * Retorna todos os dados, aplicando ordenação 
     * @param String $orderBy
     * @param String $sortBy
     * @param String $filterBy
     * @param String $filter
     * @param int $page
     */
    public function allOrder($orderBy, $sortBy='asc') {
        $sortBy = $sortBy == 'asc' || $sortBy == 'desc' ? $sortBy : 'asc';
        return $this->model->orderBy($orderBy, $sortBy)->get();
    }

    /**
     * Busca um registro
     *  @param int $Id
     *  @param String $load - utilizado para registro com relacionamentos
     */
    public function find($id, $load = null) {
        if($load == null)
            return $this->model->find($id);
        return $this->model->find($id)->load($load);
    }

    
    /**
     * Busca um registro ou gera um erro
     *  @param int $Id
     *  @param String $load - utilizado para registro com relacionamentos
     */
    public function findOrFail($id, $load = null){
        if($load == null)
            return $this->model->findOrFail($id);
        return $this->model->findOrFail($id)->load($load);
    }

    /**
     * Encontra um conjunto de grupo através do array de Ids
     *  @param String $col - corresponde a coluna da tabela
     * @param array $Ids
     */
    public function findByIds($col,array $ids){
        return $this->model->whereIn($col, $ids)->get();
    }

    /**
     * Deleta um registro
     * @param int $Id
     */
    public function delete($id) : bool{
        $this->model = $this->findOrFail($id);
        return $this->model->delete();
    }
}