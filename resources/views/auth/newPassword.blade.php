@extends('layouts.app')

@section('content')
@if (session('error'))
    <s-snackbar cor="red" msg="{{ session('error') }}"></s-snackbar>
@endif
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<s-trocarsenha token="{{ csrf_token() }}"></s-trocarsenha>
@endsection
