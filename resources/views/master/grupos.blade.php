@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-listagem  linknovo="{{ route('grupos.create') }}" linkfiltro="{{route('filtro')}}" 
    titulo="Grupos" filtroinicial="nomeGrupo" 
    :columns="[{field:'nomeGrupo', label: 'Grupo'}, {field:'idGrupo', label: 'Grupo', hidden: true}]"
    :apagar="false" :editar="false"></s-listagem>                         
@endsection
