<?php

namespace App\ Repositories;

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
        return $this->model->orderBy($orderBy, $sortBy)
                            ->where('nome', 'like', '%'.$filter.'%')
                            ->orWhere('prontuario', 'like', '%'.$filter.'%')
                            ->orWhere('email', 'like', '%'.$filter.'%')
                            ->paginate(5);
    }

    public function salvarUsuario(Request $request) {
        $this->valida($request);
        return $this->create([
            'nome' => $request->nome,
            'prontuario' => $request->prontuario,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'idGrupo' => $request->grupos
        ]);
    }
    public function atualizaUsuario(Request $request, $id) {
        $this->valida($request, $id);
        return $this->update([
            'nome' => $request->nome,
            'prontuario' => $request->prontuario,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'idGrupo' => $request->grupos
        ], $id);
    }
    private function valida(Request $request, $id = null)
    {
        if ($id == null) {
            $request->validate([
                'nome' => 'required|string|max:60|min:3',
                'prontuario' => 'required|alpha_num|max:10|min:5|unique:users,prontuario',
                'email' => 'required|string|email|max:60|unique:users,email',
                'senha' => 'required|string|min:6|confirmed',
                'grupos'=>'required'
            ]);
        } else {
            $request->validate([
                'nome' => 'required|string|max:60|min:3',
                'prontuario' => 'required|alpha_num|max:10|min:5|unique:users,prontuario,'.$id.',idUser',
                'email' => 'required|string|email|max:60|unique:users,email,'.$id.',idUser',
                'grupos'=>'required|numeric'
            ]);
        }
    }
}