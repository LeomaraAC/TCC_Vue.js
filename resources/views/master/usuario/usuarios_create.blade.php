@extends('layouts.app')
@section('content')
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-criarusuario titulo="Criar Usuário"
                            :grupos="{{$grupos}}"
                            method="POST"
                            action="{{ route('usuarios.store') }}"
                            token="{{ csrf_token() }}"
                            :valores="{
                                                nome: '{{old('nome')}}', 
                                                prontuario: '{{old('prontuario')}}',
                                                email: '{{old('email')}}',
                                                grupo:  '{{old('grupos')}}'
                                            }"
                            ></s-criarusuario>
    @foreach ($errors->all() as $error)
    <s-snackbar cor="red" msg="{{ $error }}"></s-snackbar>
        @break
    @endforeach
@endsection