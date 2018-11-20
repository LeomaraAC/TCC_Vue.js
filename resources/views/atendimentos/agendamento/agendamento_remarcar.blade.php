@extends('layouts.app')
@section('content')

    @if (session('error'))
        <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
    @endif
    @foreach ($errors->all() as $error)
        <s-snackbar cor="red" msg="{{ $error }}"></s-snackbar>
        @break
    @endforeach
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    <s-reagendamento :agendamento="{{$agendamento}}" token="{{ csrf_token() }}" />
@endsection