@extends('layouts.app')
@section('content')
<sara-breadcrumb v-bind:caminhos="{{$breadcrumb}}"></sara-breadcrumb>
<!-- card -->
<sara-card titulo="Criar Grupo" footer=true>
    <span slot="body">
        <div class="offset-md-2 col-md-8 col-sm-12">
            <sara-input>
                <input name="grupo" type="text" placeholder="Nome do grupo" class="form-control" autofocus >
            </sara-input>
        </div>
        <h5 class="text-left">Funcionalidades</h5>
        <hr>
    </span>
    <span slot="footer">
        <div class="text-left">
            <a href="{{ route('grupos.create') }}" class="btn btn-outline-info"><i class="fas fa-plus"></i> Novo</a>
        </div>
    </span>
</sara-card>
<hr>
@endsection
