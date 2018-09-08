@extends('layouts.app')
@section('content')
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-card titulo="Alunos" icon="fas fa-user-graduate"
        :footer="{{Auth::user()->can('incluir_Aluno') == true ? 'true' : 'false'}}">
        <span slot="body">
            <div class="offset-md-2 col-md-8 col-sm-12">
                <s-pesquisa></s-pesquisa>
            </div>
        </span>
        <div slot="footer" v-if="{{Auth::user()->can('incluir_Aluno') == true ? 'true' : 'false'}} || 
                                {{Auth::user()->can('importar_Alunos') == true ? 'true' : 'false'}}" class="row">
            @if(Auth::user()->can('incluir_Aluno'))
                <a href="{{route('alunos.create')}}" class="btn btn-outline-info mr-2"><i class="fas fa-plus"></i> Novo aluno</a>
            @endif
            @if(Auth::user()->can('importar_Alunos'))
            <s-modalimport token="{{csrf_token()}}" />
            @endif
        </div>
    </s-card>
    <hr>
    <s-listagemalunos linkacoes="{{route('alunos.index')}}" linkfiltro="{{route('alunos.filtro')}}"
    :permissao_apagar="{{Auth::user()->can('excluir_Aluno') == true ? 'true' : 'false'}}"
    :permissao_editar="{{Auth::user()->can('editar_Aluno') == true ? 'true' : 'false'}}"
    :columns="{{$columns}}" token="{{csrf_token()}}"
    >
    </s-listagemalunos>

@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif         
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif            
@endsection