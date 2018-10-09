@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-formulariotipo descricao="{{old('descricao')}}"
    method="POST" action="{{ route('tipo.store') }}" token="{{ csrf_token() }}" />
@if ($errors->has('descricao'))
    <s-snackbar cor="red" msg="{{ $errors->first('grupo') }}"></s-snackbar>
@endif
@endsection
