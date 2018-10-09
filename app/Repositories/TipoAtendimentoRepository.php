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
        $this->validaInserir($request);
        return $this->create([
            'descricao' => $request->descricao
        ]);
    }

    private function validaInserir(Request $request) {
        $request->validate([
            'descricao' => 'bail|required|string|max:100|min:3|unique:tipo_atendimento,descricao',
        ]);
    }

    public function filtro($orderBy = 'descricao',$sortBy = 'asc', $filter = null){
        return $this->allPaginate($orderBy, $sortBy,"descricao", $filter);
    }
}