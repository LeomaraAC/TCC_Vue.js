@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-listagem  linknovo="{{ route('usuarios.create') }}" linkfiltro="{{route('usuarios.filtro')}}" 
    titulo="Usuários" filtroinicial="nome" :columns="{{$columns}}"
    :adicionar="{{Auth::user()->can('incluir_Grupo') == true ? 'true' : 'false'}}"
    :apagar="true" :editar="true"  linkacoes="{{route('usuarios.index')}}" token="{{ csrf_token() }}" icon="fas fa-users-cog">
</s-listagem>    
@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif         
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif            
@endsection
