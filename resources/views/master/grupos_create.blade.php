@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-criargrupo link="http://projetosara.meu/master/permissoes"></s-criargrupo>

@endsection
