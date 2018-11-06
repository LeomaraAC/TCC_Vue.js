@extends('layouts.app')

@section('content')
@if (session('success'))
    <s-snackbar cor="green" msg="{{ session('success') }}"></s-snackbar>
@endif 

@if(Auth::user()->can('agendamento'))
<div class="row">
    <div class="col-md-6 col-sm-12">
        <s-box color="success">
            <span slot="title">
                As próximas 5 reuniões.
            </span>
            <s-visualizacaohome :columns="{{$columns}}" :rows="{{$rowsProximas}}" id="proximas"></s-visualizacaohome>
        </s-box>
    </div>
    <div class="col-md-6 col-sm-12">
        <s-box color="success">
            <span slot="title">
                As últimas 5 reuniões atrasadas.
            </span>
            <s-visualizacaohome :columns="{{$columns}}" :rows="{{$rowsAtrasadas}}" id="atrasadas"></s-visualizacaohome>
        </s-box>
    </div>
</div>
@endif


@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif         
@endsection
