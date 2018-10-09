<?php
namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Http\Request;
use App\TipoAtendimento;

class TipoAtendimentoRepository extends BaseRepository {
    protected $model;

    public function __construct(TipoAtendimento $tipoAtendimento) {
        $this->model = $tipoAtendimento;
    }

    public function salvarTipo(Request $request) {
        $this->validacao($request);
        return $this->create([
            'descricao' => $request->descricao
        ]);
    }

    private function validacao(Request $request) {
        return true;
    }

    public function filtro($orderBy = 'descricao',$sortBy = 'asc', $filter = null){
        return $this->allPaginate($orderBy, $sortBy,"descricao", $filter);
    }
}