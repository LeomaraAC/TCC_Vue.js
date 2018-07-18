@extends('layouts.app')

@section('content')
<div class="row justify-content-center fundo">
        <div class="col">
            <sara-login old="{{old('prontuario')}}"  url="{{ route('password.request') }}" token= "{{ csrf_token() }}" 
            action="{{ route('login') }}"></sara-login>
        </div>
</div>
@if ($errors->has('prontuario'))
    <sara-snackbar cor="red" msg="{{ $errors->first('prontuario') }}"></sara-snackbar>
@elseif ($errors->has('password'))
    <sara-snackbar cor="red" msg="{{ $errors->first('password') }}"></sara-snackbar>
@endif
@endsection
