@extends('layouts.app')
@section('content')
@if ($errors->has('descricao'))
    <s-snackbar cor="red" msg="{{ $errors->first('descricao') }}"></s-snackbar>
@endif
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-formulariotipo descricao="{{old('descricao')}}"
    method="POST" action="{{ route('tipo.store') }}" token="{{ csrf_token() }}" />
@endsection
