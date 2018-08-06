@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-listagem  linknovo="{{ route('grupos.create') }}" linkfiltro="{{route('grupos.filtro')}}" 
    titulo="Grupos" filtroinicial="nomeGrupo" 
    :columns="[
        {field:'idGrupo',hidden: true}, 
        {field:'deletar', label: '', width: '50px', sortable: false}, 
        {field:'editar', label: '', width: '50px', sortable: false}, 
        {field:'nomeGrupo', label: 'Grupo'}]"
    :apagar="true" :editar="true" linkacoes="{{ route('grupos.index') }}" token="{{ csrf_token() }}" icon="fas fa-shapes"></s-listagem>    
@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif         
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif            
@endsection
