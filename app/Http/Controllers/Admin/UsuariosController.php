<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Grupo;

class UsuariosController extends Controller
{
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
        return User::orderBy($campo, $order)
                            ->where('nome', 'like', '%'.$filter.'%')
                            ->orWhere('prontuario', 'like', '%'.$filter.'%')
                            ->orWhere('email', 'like', '%'.$filter.'%')
                            ->paginate(5);
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
        $grupos = Grupo::orderBy('nomeGrupo', 'asc')->get();
        return view('master.usuario.usuarios_create', compact('breadcrumb', 'grupos'));
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
        User::create([
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return 'edit';
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
        return 'update';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $usuario = User::find($id);
        if($usuario != null) {
            $usuario->delete();
            return redirect()->route('usuarios.index')->with('success', 'Usuário '.$usuario->nome.' excluído  com sucesso!');
        }else
            return redirect()->route('usuarios.index')->with('error', 'Ops! O usuário a ser excluído  não foi encontrado.');
    }
}
