@extends('layouts.app')

@section('content')
<div class="row justify-content-center fundo">
        <div class="col">
            <s-login old="{{old('prontuario')}}"  url="{{ route('password.request') }}" token= "{{ csrf_token() }}" 
            action="{{ route('login') }}"></s-login>
        </div>
</div>
@if ($errors->has('prontuario'))
    <s-snackbar cor="red" msg="{{ $errors->first('prontuario') }}"></s-snackbar>
@elseif ($errors->has('password'))
    <s-snackbar cor="red" msg="{{ $errors->first('password') }}"></s-snackbar>
@endif
@endsection
