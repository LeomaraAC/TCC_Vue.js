@extends('layouts.app')
@section('content')
    <s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
    @foreach ($errors->all() as $error)
        <s-snackbar cor="red" msg="{{ $error }}"></s-snackbar>
        @break
    @endforeach
    <!-- <s-formularioalunos action="{{route('alunos.store')}}" token="{{csrf_token()}}"
    method="POST" :valores="{
                                nome: '{{old('nome')}}', 
                                prontuario: '{{old('prontuario')}}',
                                email: '{{old('email')}}',
                                telefone:  '{{old('telefone')}}',
                                semestre:  '{{old('semestre')}}',
                                observacao:  '{{old('observacao')}}',
                                curso:  '{{old('curso')}}',
                            }"/> -->

@endsection