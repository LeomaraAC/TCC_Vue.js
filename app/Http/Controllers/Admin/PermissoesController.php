<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permissao;
use App\Repositories\PermissaoRepository;

class PermissoesController extends Controller
{
    
    public function __construct(PermissaoRepository $permissoesRepository) {
        $this->permissoesRepository = $permissoesRepository;
    }

    /**
     * Retorna a lista de permissões de forma paginada, ordenada e oferece possibilidade de filtro
     */
    public function index($campo = 'idTelas',$order = 'asc', $filter = null) {
        return $this->permissoesRepository->allPaginate($campo, $order,'descricao', $filter,5);
    }

    /**
     * Busca os dados de um determinado conjunto de Ids
     * @param  \Illuminate\Http\Request  $request
     */
    public function getByIds (Request $request) {
        return  $this->permissoesRepository->findByIds('idTelas', $request->ids);
    }
}
