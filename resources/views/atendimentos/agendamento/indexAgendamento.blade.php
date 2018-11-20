@extends('layouts.app')
@section('content')
    @if (session('success'))
        <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
    @endif         
    @if (session('error'))
        <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
    @endif    
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-card titulo="Agendamentos" icon="fas fa-calendar-alt"
        :footer="{{Auth::user()->can('agendar_Atendimento') == true ? 'true' : 'false'}}">
        <span slot="body">
                <s-pesquisaagendamento></s-pesquisaagendamento>
        </span>
        <span slot="footer" v-if="{{Auth::user()->can('agendar_Atendimento') == true ? 'true' : 'false'}}">
            @if(Auth::user()->can('agendar_Atendimento'))
                <div class="text-left">
                    <a id="novoTipo" href="{{route('agendamento.create')}}" class="btn btn-outline-info"><i class="fas fa-plus"></i> Novo</a>
                </div>
            @endif
        </span>
    </s-card>
    <hr>
    <s-listagemagendamento linkfiltro="{{route('agendamento.filtro')}}"
        :columns="{{$columns}}"
    />        
@endsection