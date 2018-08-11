<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\GrupoRepository;

class GruposController extends Controller
{
    /**
     * Construtor recebe a instancia do repositório
     */
    public function __construct(GrupoRepository $grupoRepository) {
        $this->grupoRepository = $grupoRepository;
    }

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
        return view('master.grupo.indexGrupo', compact('breadcrumb'));
    }

    /**
     * Retorna os dados paginados e com filtro
     * @param String $campo
     * @param String $order
     * @param String $filter
     */
    public function filtro($campo = 'idGrupo',$order = 'asc', $filter = null){
        return $this->grupoRepository->allPaginate($campo, $order,'nomeGrupo', $filter);
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
        $this->grupoRepository->createGrupo($request->all());
        return redirect()->route('grupos.index')->with('success', 'Grupo criado com sucesso!');
    }

    private function valida ($request, $id = null) {
        if ($id == null) {
            $request->validate([
                'grupo' =>'bail|required|unique:grupo,nomeGrupo|min:3|max:60|regex:/^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$/',
                'idTelas' =>'required'
            ]);
        } else {
            $request->validate([
                'grupo' =>'bail|required|min:3|max:60|regex:/^[a-zA-Z0-9\\- áÁéÉíÍóÓúÚçÇ`àÀãÃõÕôÔêÊ_]+$/|unique:grupo,nomeGrupo,'.$id.',idGrupo',
                'idTelas' =>'required'
            ]);
        }
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
        $grupo = $this->grupoRepository->find($id, 'funcoes');
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
        
        $this->valida($request,$id);
        $this->grupoRepository->updateGrupo($request->all(), $id);
       // Redireciona para a página da listagem dos grupos juntamente com a mensagem de sucesso.
        return redirect()->route('grupos.index')->with('success', 'Grupo editado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->grupoRepository->delete($id)) {
            return redirect()->route('grupos.index')->with('success', 'Grupo excluído  com sucesso!');
        }
        else
            return redirect()->route('grupos.index')->with('error', 'Ops! O grupo a ser excluído  não foi encontrado.');
    }
}
