@extends('layouts.app')
@section('content')
    @if (session('success'))
        <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
    @endif         
    @if (session('error'))
        <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
    @endif    

    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-card titulo="Atendimentos" icon="fas fa-headset">
        <span slot="body">
            <div class="offset-md-2 col-md-8 col-sm-12">
                <s-pesquisa></s-pesquisa>
            </div>
        </span>
    </s-card>
    <hr>
    <s-listagematendimento linkfiltro="{{route('atendimento.filtro')}}"
        :columns="{{$columns}}"
    />
@endsection