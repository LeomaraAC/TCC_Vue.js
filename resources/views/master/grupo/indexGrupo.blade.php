@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-listagem  linknovo="{{ route('grupos.create') }}" linkfiltro="{{route('grupos.filtro')}}" 
    titulo="Grupos" filtroinicial="nomeGrupo" 
    :columns="{{$columns}}"
    :apagar="{{Auth::user()->can('excluir_Grupo') == true ? 'true' : 'false'}}" 
    :editar="{{Auth::user()->can('editar_Grupo') == true ? 'true' : 'false'}}" 
    linkacoes="{{ route('grupos.index') }}" token="{{ csrf_token() }}" icon="fas fa-shapes"></s-listagem>    
@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif         
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif            
@endsection
