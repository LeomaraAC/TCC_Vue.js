@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-listagem  linknovo="{{ route('usuarios.create') }}" linkfiltro="{{route('usuarios.filtro')}}" 
    titulo="Usuários" filtroinicial="nome" 
    :columns="[
        {field:'idUser',hidden: true}, 
        {field:'deletar', label: '', width: '50px',sortable: false}, 
        {field:'editar', label: '', width: '50px', sortable: false}, 
        {field:'nome', label: 'Usuário'}, 
        {field:'prontuario', label: 'Prontuário'}, 
        {field:'email', label: 'Email'}]"
    :apagar="true" :editar="true"  linkacoes="{{route('usuarios.index')}}" token="{{ csrf_token() }}" icon="fas fa-users-cog">
</s-listagem>    
@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif         
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif            
@endsection
