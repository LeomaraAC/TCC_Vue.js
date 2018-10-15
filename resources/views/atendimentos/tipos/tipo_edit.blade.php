@extends('layouts.app')
@section('content')
@if ($errors->has('descricao'))
    <s-snackbar cor="red" msg="{{ $errors->first('descricao') }}"></s-snackbar>
@endif
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-formulariotipo descricao="{{$tipo->descricao}}"
    method="PUT" action="/atendimento/tipo/{{$tipo->idTipo_atendimento}}" token="{{ csrf_token() }}" />
@endsection
