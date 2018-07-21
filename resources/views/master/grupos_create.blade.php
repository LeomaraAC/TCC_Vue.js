@extends('layouts.app')
@section('content')
<sara-breadcrumb :caminhos="{{$breadcrumb}}"></sara-breadcrumb>
<sara-criargrupo link="http://projetosara.meu/master/permissoes"></sara-criargrupo>

@endsection
