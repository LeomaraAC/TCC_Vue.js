@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center fundo">
        <div class="login-form">
            <div class="row justify-content-center mb-3">
                <h1><span class="font">SARA</span><small> Campus Capivari</small></h1>
                <h4><span>Recuperar Senha</span></h4>
            </div>
            <form method="POST" action="{{ route('password.request') }}" aria-label="{{ __('Reset Password') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <s-input  icon="fas fa-envelope">
                    <input name="email" type="email" 
                        placeholder="Email" autofocus
                        :class="{'form-control form-control-warning': true, 'is-invalid': {{$errors->has('email') ? 'true' : 'false'}} }"
                    >
                    <span slot="error">
                    {{ $errors->first('email') }}
                    </span>
                </s-input>

                <s-input  icon="fas fa-key">
                    <input name="password" type="password" 
                        placeholder="Nova Senha" autofocus
                        :class="{'form-control form-control-warning': true, 'is-invalid': {{$errors->has('password') ? 'true' : 'false'}} }"
                    >
                    <span slot="error">
                    {{ $errors->first('password') }}
                    </span>
                </s-input>

                <s-input  icon="fas fa-key">
                    <input name="password_confirmation" type="password" 
                        placeholder="Confirmar Senha" autofocus
                        class="form-control form-control-warning"
                    >
                </s-input>
                <button type="submit" class="log-btn btn-primary">Recuperar Senha </button>
            </form>
        </div>
    </div>
</div>
@endsection
