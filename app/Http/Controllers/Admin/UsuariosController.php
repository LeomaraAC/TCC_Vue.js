<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Repositories\UserRepository;
use App\Grupo;
use Illuminate\Support\Facades\Gate;

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
        if(Gate::denies('usuario'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        $columns = json_encode($this->getColunas());
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Usuários", "url" =>""]
        ]);
        return view('master.usuario.indexUsuario', compact('breadcrumb', 'columns'));
    }

    private function getColunas() {
        $columns = array(["field"=>"idUser", "hidden" =>true]);
        if (Gate::allows('excluir_User'))
            array_push($columns,["field"=>"deletar", "label" =>'', "width"=> '30px', "sortable"=>false]);

        if (Gate::allows('editar_User'))
            array_push($columns,["field"=>"editar", "label" =>'', "width"=> '30px', "sortable"=>false]);
            
        array_push($columns,["field"=>"nome", "label" =>"Usuário"]);
        array_push($columns,["field"=>"prontuario", "label" =>"Prontuário"]);
        array_push($columns,["field"=>"email", "label" =>"Email"]);
        array_push($columns,["field"=>"nomeGrupo", "label" =>"Grupo"]);
        return $columns;
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
        if(Gate::denies('incluir_User'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
    
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
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('incluir_User'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        $this->userRepository->salvarUsuario($request);
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
        if(Gate::denies('editar_User'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

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
        if(Gate::denies('editar_User'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
            
        $this->userRepository->atualizaUsuario($request, $id);
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
        if(Gate::denies('excluir_User'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        if($this->userRepository->delete($id)) {
            return redirect()->route('usuarios.index')->with('success', 'Usuário '.$usuario->nome.' excluído  com sucesso!');
        }else
            return redirect()->route('usuarios.index')->with('error', 'Ops! O usuário a ser excluído  não foi encontrado.');
    }
}
