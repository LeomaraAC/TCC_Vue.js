@extends('layouts.app')
@section('content')
    
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-formularioagendamento method="POST" action="{{ route('agendamento.store') }}" 
                        token="{{ csrf_token() }}" :alunos="{{$alunos}}" :tipos="{{$tipos}}"/>
@endsection