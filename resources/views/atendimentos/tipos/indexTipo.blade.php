@extends('layouts.app')
@section('content')
    @if (session('success'))
        <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
    @endif         
    @if (session('error'))
        <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
    @endif    
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-card titulo="Tipos de atendimento" icon="fas fa-tag"
        :footer="{{Auth::user()->can('incluir_Tipo_Atendimento') == true ? 'true' : 'false'}}">
        <span slot="body">
            <div class="offset-md-2 col-md-8 col-sm-12">
                <s-pesquisa></s-pesquisa>
            </div>
        </span>
        <span slot="footer" v-if="{{Auth::user()->can('incluir_Tipo_Atendimento') == true ? 'true' : 'false'}}">
            @if(Auth::user()->can('importar_Alunos'))
                <div class="text-left">
                    <a id="novoTipo" href="{{route('tipo.create')}}" class="btn btn-outline-info"><i class="fas fa-plus"></i> Novo</a>
                </div>
            @endif
        </span>
    </s-card>
    <hr>
    <s-listagemtipo linkacoes="{{route('tipo.index')}}" linkfiltro="{{route('tipo.filtro')}}"
        :columns="{{$columns}}" token="{{ csrf_token() }}"
        :apagar="{{Auth::user()->can('excluir_Tipo_Atendimento') == true ? 'true' : 'false'}}" 
        :editar="{{Auth::user()->can('editar_Tipo_Atendimento') == true ? 'true' : 'false'}}"
    />        
@endsection