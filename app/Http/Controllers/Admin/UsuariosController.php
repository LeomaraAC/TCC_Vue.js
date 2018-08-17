<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Repositories\UserRepository;
use App\Grupo;

class UsuariosController extends Controller
{
    /**
     * Construtor recebe a instancia do repositório
     */
    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Usuários", "url" =>""]
        ]);
        return view('master.usuario.indexUsuario', compact('breadcrumb'));
    }
    public function filtro($campo = 'idUser',$order = 'asc', $filter = null){
        return $this->userRepository->filtro($campo,$order, $filter);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Usuários", "url" =>route('usuarios.index')],
            ["titulo"=>"Criar Usuário", "url" =>""]
        ]);
        $grupos = $this->getGrupo();
        return view('master.usuario.usuarios_create', compact('breadcrumb', 'grupos'));
    }
    private function getGrupo() {
        return $grupos = Grupo::orderBy('nomeGrupo', 'asc')->get();
    }
    private function valida($request, $id = null)
    {
        if ($id == null) {
            $request->validate([
                'nome' => 'required|string|max:60|min:3',
                'prontuario' => 'required|alpha_num|max:10|min:5|unique:users,prontuario',
                'email' => 'required|string|email|max:60|unique:users,email',
                'senha' => 'required|string|min:6|confirmed',
                'grupos'=>''
            ]);
        } else {
            $request->validate([
                'nome' => 'required|string|max:60|min:3',
                'prontuario' => 'required|alpha_num|max:10|min:5|unique:users,prontuario,'.$id.',idUser',
                'email' => 'required|string|email|max:60|unique:users,email,'.$id.',idUser',
                'senha' => 'required|string|min:6|confirmed',
                'grupos'=>'required|numeric'
            ]);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->valida($request);
        $this->userRepository->create([
            'nome' => $request->nome,
            'prontuario' => $request->prontuario,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'idGrupo' => $request->grupos
        ]);
       // Redireciona para a página da listagem dos grupos juntamente com a mensagem de sucesso.
        return redirect()->route('usuarios.index')->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = $this->userRepository->findOrFail($id);
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Usuários", "url" =>route('usuarios.index')],
            ["titulo"=>"Editar Usuários", "url" =>""]
        ]);
        $grupos = $this->getGrupo();
        return view('master.usuario.usuarios_edit', compact('breadcrumb', 'usuario','grupos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->valida($request, $id);
        $this->userRepository->update([
            'nome' => $request->nome,
            'prontuario' => $request->prontuario,
            'email' => $request->email,
            'password' => Hash::make($request->senha),
            'idGrupo' => $request->grupos
        ], $id);
        return redirect()->route('usuarios.index')->with('success', 'Usuário '.$request->nome.' editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->userRepository->delete($id)) {
            return redirect()->route('usuarios.index')->with('success', 'Usuário '.$usuario->nome.' excluído  com sucesso!');
        }else
            return redirect()->route('usuarios.index')->with('error', 'Ops! O usuário a ser excluído  não foi encontrado.');
    }
}
