@extends('layouts.app')
@section('content')
<sara-breadcrumb :caminhos="{{$breadcrumb}}"></sara-breadcrumb>
<sara-listagem  linknovo="{{ route('grupos.create') }}" linkfiltro="{{route('filtro')}}" 
    titulo="Grupos" filtroinicial="nomeGrupo" :header="[{id:'nomeGrupo', label: 'Grupo'}]"
    :exceto="['idGrupo']" :apagar="false" :editar="false"></sara-listagem>
                         
@endsection
