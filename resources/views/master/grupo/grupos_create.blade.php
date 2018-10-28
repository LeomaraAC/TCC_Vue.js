@extends('layouts.app')
@section('content')
    @if (session('success'))
        <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
    @endif   
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-criargrupo nomegrupo="{{old('grupo')}}" titulo="Criar Grupo"  dadosselecionados="{{old('idTelas')}}"
        method="POST" action="{{ route('grupos.store') }}" token="{{ csrf_token() }}"  icon="fas fa-shapes"></s-criargrupo>
    @if ($errors->has('grupo'))
        <s-snackbar cor="red" msg="{{ $errors->first('grupo') }}"></s-snackbar>
    @elseif ($errors->has('idTelas'))
        <s-snackbar cor="red" msg="Selecione pelo menos uma permissão para poder continuar"></s-snackbar>
    @endif
@endsection
