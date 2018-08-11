<?php
namespace App\ Repositories\Contracts\Base;

interface BaseRepositoryInterface {

    public function create(array $dados);
    public function update(array $dados, $id) : bool;
    public function allPaginate($orderBy, $sortBy='asc', $filterBy, $filter=null, $page=5);
    public function allOrder($orderBy, $sortBy='asc');
    public function find($id, $load = null);
    public function findOrFail($id, $load = null);
    public function findByIds($col,array $ids);
    public function delete($id) : bool;
}