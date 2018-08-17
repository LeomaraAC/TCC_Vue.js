@extends('layouts.app')
@section('content')
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-criarusuario titulo="Editar Usuário"
                            :grupos="{{$grupos}}"
                            method="PUT"
                            action="/master/usuarios/{{$usuario->idUser}}"
                            token="{{ csrf_token() }}"
                            :editar="true"
                            :valores="{
                                                nome: '{{$usuario->nome}}', 
                                                prontuario: '{{$usuario->prontuario}}',
                                                email: '{{$usuario->email}}',
                                                grupo:  '{{$usuario->idGrupo}}'
                                            }"
                            ></s-criarusuario>
    @foreach ($errors->all() as $error)
        <s-snackbar cor="red" msg="{{ $error }}"></s-snackbar>
        @break
    @endforeach
@endsection
