<?php

namespace App\ Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\User;


class UserRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(User $user) {
        $this->model = $user;
    }
    
    /**
     * Função que retorna os dados paginado, ordenado e filtrado.
     * @param String $orderBy
     * @param String $sortBy
     * @param String $filter
     */
    public function filtro($orderBy = 'idUser',$sortBy = 'asc', $filter = null){
        return $this->model->orderBy($orderBy, $sortBy)
                            ->where('nome', 'like', '%'.$filter.'%')
                            ->orWhere('prontuario', 'like', '%'.$filter.'%')
                            ->orWhere('email', 'like', '%'.$filter.'%')
                            ->paginate(5);
    }
}