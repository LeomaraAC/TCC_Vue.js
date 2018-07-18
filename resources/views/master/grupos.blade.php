@extends('layouts.app')
@section('content')
<sara-breadcrumb v-bind:caminhos="{{$breadcrumb}}"></sara-breadcrumb>
<!-- card -->
<sara-card titulo="Grupos" footer=true>
    <span slot="body">
        <div class="offset-md-2 col-md-8 col-sm-12">
            <sara-input  icon="fas fa-search">
                <input name="busca" type="text" placeholder="Buscar...." class="form-control" autofocus >
            </sara-input>
        </div>
    </span>
    <span slot="footer">
        <div class="text-left">
            <a href="{{ route('grupos.create') }}" class="btn btn-outline-info"><i class="fas fa-plus"></i> Novo</a>
        </div>
    </span>
</sara-card>
<hr>
<sara-card>
    <span slot="body">
        <sara-tabela></sara-tabela>
    </span>
</sara-card>
@endsection
