@extends('layouts.app')
@section('content')
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-criarusuario titulo="Criar Usuário"
                            icon="fas fa-users-cog"
                            :grupos="{{$grupos}}"
                            method="POST"
                            action="{{ route('usuarios.store') }}"
                            token="{{ csrf_token() }}"
                            ></s-criarusuario>
@endsection