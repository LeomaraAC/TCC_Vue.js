@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-criargrupo link="http://projetosara.meu/master/permissoes" nomegrupo="{{old('grupo')}}" titulo="Criar Grupo"
    method="POST" action="{{ route('grupos.store') }}" token="{{ csrf_token() }}"  icon="fas fa-shapes"></s-criargrupo>
@if ($errors->has('grupo'))
    <s-snackbar cor="red" msg="{{ $errors->first('grupo') }}"></s-snackbar>
@elseif ($errors->has('idTelas'))
    <s-snackbar cor="red" msg="Selecione pelo menos uma permissão para poder continuar"></s-snackbar>
@endif
@endsection
