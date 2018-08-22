<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\GrupoRepository;
use Illuminate\Support\Facades\Gate;

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
        if(Gate::denies('grupo'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

        $columns = json_encode($this->getColunas());
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Grupos", "url" =>""]
            ]);
        return view('master.grupo.indexGrupo', compact('breadcrumb', 'columns'));           
    }

    private function getColunas() {
        $columns = array(["field"=>"idGrupo", "hidden" =>true]);
        if (Gate::allows('excluir_Grupo'))
            array_push($columns,["field"=>"deletar", "label" =>'', "width"=> '50px', "sortable"=>false]);

        if (Gate::allows('editar_Grupo'))
            array_push($columns,["field"=>"editar", "label" =>'', "width"=> '50px', "sortable"=>false]);
            
        array_push($columns,["field"=>"nomeGrupo", "label" =>"Grupo"]);
        return $columns;
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
        if(Gate::denies('incluir_Grupo'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

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
        if (Gate::denies('incluir_Grupo')) 
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Gate::denies('editar_Grupo')) 
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
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
        if (Gate::denies('editar_Grupo')) 
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
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
        if (Gate::denies('excluir_Grupo')) 
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        if($this->grupoRepository->delete($id)) {
            return redirect()->route('grupos.index')->with('success', 'Grupo excluído  com sucesso!');
        }
        else
            return redirect()->route('grupos.index')->with('error', 'Ops! O grupo a ser excluído  não foi encontrado.');
    }
}
