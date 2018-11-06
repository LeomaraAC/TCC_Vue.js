@extends('layouts.app')

@section('content')
    @if (session('status'))
        <s-snackbar cor="green" msg="{{ session('status') }}"></s-snackbar>
    @endif

    <div class="row justify-content-center fundo">
        <div class="login-form">
            <div class="row justify-content-center mb-3">
                <h1><span class="font">SARA</span><small> Campus Capivari</small></h1>
                <h4><span>Recuperar Senha</span></h4>
            </div>
            <form method="POST" action="{{ route('password.email') }}" aria-label="{{ __('Reset Password') }}">
                @csrf
                <s-input  icon="fas fa-envelope">
                    <input name="email" type="email" 
                        placeholder="Email" autofocus
                        :class="{'form-control form-control-warning': true, 'is-invalid': {{$errors->has('email') ? 'true' : 'false'}} }"
                    >
                    <span slot="error">
                    {{ $errors->first('email') }}
                    </span>
                </s-input>
                <button type="submit" class="log-btn btn-primary">Recuperar Senha </button>
            </form>
            <p class="mt-2 mb-2 text-muted text-center"><a href="{{route('login')}}">< Login</a></p>
        </div>
    </div>
@endsection
