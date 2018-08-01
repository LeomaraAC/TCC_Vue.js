<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Grupo as Grupo;
use App\Permissao;

class GruposController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Grupos", "url" =>""]
        ]);
        return view('master.grupo.index', compact('breadcrumb'));
    }
    public function filtro($campo = 'idGrupo',$order = 'asc', $filter = null){
        return Grupo::orderBy($campo, $order)->where('nomeGrupo', 'like', '%'.$filter.'%')->paginate(5);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Grupos", "url" =>route('grupos.index')],
            ["titulo"=>"Criar Grupo", "url" =>""]
        ]);
        return view('master.grupo.grupos_create', compact('breadcrumb'));
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
        $permissoes = explode(',', $request->idTelas);
        $grupo = new Grupo;
        $grupo->nomeGrupo = $request->grupo;
        $grupo->save();
        // Removendo possíveis  duplicidade de funções
        $funcoes = array_unique($permissoes);
        // Informando as permssoes do grupo
        foreach ($funcoes as $idFuncao) {
            $funcao = Permissao::find($idFuncao);
            if ($funcao != null)
                $grupo->funcoes()->attach($funcao->idTelas);
        }
        return redirect()->route('grupos.index')->with('success', 'Grupo criado com sucesso!');
    }

    private function valida ($request) {
        
        $request->validate([
            'grupo' =>'bail|required|unique:grupo,nomeGrupo|min:3|max:60|regex:/^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$/',
            'idTelas' =>'required'
        ]);
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
        $grupo = Grupo::findOrFail($id)->load('funcoes');
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Grupos", "url" =>route('grupos.index')],
            ["titulo"=>"Editar Grupo", "url" =>""]
        ]);
        return view('master.grupo.grupos_edit', compact('breadcrumb', 'grupo'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $grupo = Grupo::find($id);
        if($grupo != null) {
            $grupo->funcoes()->detach();
            $grupo->delete();
            return redirect()->route('grupos.index')->with('success', 'Grupo excluído  com sucesso!');
        }
        else
            return redirect()->route('grupos.index')->with('error', 'Ops! O grupo a ser excluído  não foi encontrado.');
    }
}
