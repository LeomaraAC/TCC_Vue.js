@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>

<s-criargrupo nomegrupo="{{$grupo->nomeGrupo}}" idgrupo="{{$grupo->idGrupo}}" titulo="Editar Grupo"
    method="PUT" action="/master/grupos/{{$grupo->idGrupo}}" token="{{ csrf_token() }}"  
    icon="fas fa-shapes" :dadosselecionados="{{$grupo->funcoes}}">
</s-criargrupo>

@if ($errors->has('grupo'))
    <s-snackbar cor="red" msg="{{ $errors->first('grupo') }}"></s-snackbar>
@elseif ($errors->has('idTelas'))
    <s-snackbar cor="red" msg="Selecione pelo menos uma permissão para poder continuar"></s-snackbar>
@endif


@endsection
