@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-listagem  linknovo="{{ route('grupos.create') }}" linkfiltro="{{route('filtro')}}" 
    titulo="Grupos" filtroinicial="nomeGrupo" 
    :columns="[{field:'idGrupo', label: '', width: '50px'}, {field:'nomeGrupo', label: 'Grupo'}]"
    apagar=" /master/grupos/" :editar="false" token="{{ csrf_token() }}" icon="fas fa-shapes"></s-listagem>    
@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif         
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif            
@endsection
