@extends('layouts.app')

@section('content')
<h1>Ola</h1>
<h5>{{Auth::user()->can('editar_Grupo') == true ? 'true' : 'false'}}</h5>

@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif         
@endsection
