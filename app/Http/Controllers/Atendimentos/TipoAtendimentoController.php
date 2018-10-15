<?php

namespace App\Http\Controllers\Atendimentos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Repositories\TipoAtendimentoRepository;

class TipoAtendimentoController extends Controller
{

    public function __construct(TipoAtendimentoRepository $tipo) {
        $this->repo = $tipo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //allPaginate
        if(Gate::denies('tipo_atendimento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        $columns = json_encode($this->getColunas());
        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Tipo de atendimento", "url" =>""]
        ]);
        return view('atendimentos.tipos.indexTipo', compact('breadcrumb', 'columns'));
    }

    private function getColunas() {
        $columns = array(["field"=>"idTipo_atendimento", "hidden" =>true]);
        if (Gate::allows('excluir_Tipo_Atendimento'))
            array_push($columns,["field"=>"deletar", "label" =>'', "width"=> '30px', "sortable"=>false]);

        if (Gate::allows('editar_Tipo_Atendimento'))
            array_push($columns,["field"=>"editar", "label" =>'', "width"=> '30px', "sortable"=>false]);
            
        array_push($columns,["field"=>"descricao", "label" =>"Descrição"]);
        return $columns;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Gate::denies('incluir_Tipo_Atendimento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Tipo de atendimento", "url" =>route('tipo.index')],
            ["titulo"=>"Criar Tipo de atendimento", "url" =>""]
        ]);
        return view('atendimentos.tipos.tipo_create', compact('breadcrumb'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Gate::denies('incluir_Tipo_Atendimento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
    
        $this->repo->salvarTipo($request);
        // Redireciona para a página da listagem dos grupos juntamente com a mensagem de sucesso.
        return redirect()->route('tipo.index')->with('success', 'Tipo de atendimento criado com sucesso!');
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
        if(Gate::denies('editar_Tipo_Atendimento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');

        $breadcrumb = json_encode([
            ["titulo"=>"Home", "url" =>route('home')],
            ["titulo"=>"Tipo de atendimento", "url" =>route('tipo.index')],
            ["titulo"=>"Editar Tipo de atendimento", "url" =>""]
        ]);

        $tipo = $this->repo->findOrFail($id);

        return view('atendimentos.tipos.tipo_edit', compact('breadcrumb', 'tipo'));
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
        if(Gate::denies('excluir_Tipo_Atendimento'))
            return redirect()->back()->with('error', 'Ops! Acesso negado.');
        
        if($this->repo->delete($id)) {
            return redirect()->back()->with('success', 'Tipo de atendimento excluído com sucesso!');
        }else
            return redirect()->route('tipo.index')->with('error', 'Ops! O tipo de atendimento a ser excluído não foi encontrado.');
    }

    public function filtro($campo = 'descricao',$order = 'asc', $filter = null){
        return $this->repo->filtro($campo,$order, $filter);
    }
}
