<?php

namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        return $this->model->join('grupo','users.idGrupo', '=', 'grupo.idGrupo')
                            ->orderBy($orderBy, $sortBy)
                            ->where('nome', 'like', '%'.$filter.'%')
                            ->orWhere('prontuario', 'like', '%'.$filter.'%')
                            ->orWhere('email', 'like', '%'.$filter.'%')
                            ->select('users.idUser','users.nome', 'users.prontuario', 'users.email', 'grupo.nomeGrupo')
                            ->paginate(25);
    }

    public function salvarUsuario(Request $request) {
        $this->validaInserir($request);
        return $this->create([
            'nome' => $request->nome,
            'prontuario' => $request->prontuario,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'idGrupo' => $request->grupos
        ]);
    }
    public function atualizaUsuario(Request $request, $id) {
        $this->validaEditar($request, $id);
        return $this->update([
            'nome' => $request->nome,
            'prontuario' => $request->prontuario,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'idGrupo' => $request->grupos
        ], $id);
    }
    private function validaInserir(Request $request)
    {
        $request->validate([
            'nome' => 'bail|required|string|max:60|min:3',
            'prontuario' => 'bail|required|alpha_num|max:10|min:5|unique:users,prontuario',
            'email' => 'bail|required|string|email|max:60|unique:users,email',
            'senha' => 'bail|required|string|min:6|confirmed',
            'grupos'=>'bail|required'
        ]);
    }
    private function validaEditar(Request $request, $id)
    {
        $request->validate([
            'nome' => 'bail|required|string|max:60|min:3',
            'prontuario' => 'bail|required|alpha_num|max:10|min:5|unique:users,prontuario,'.$id.',idUser',
            'email' => 'bail|required|string|email|max:60|unique:users,email,'.$id.',idUser',
            'grupos'=>'bail|required|numeric'
        ]);
    }
}