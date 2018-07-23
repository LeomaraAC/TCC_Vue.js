@extends('layouts.app')
@section('content')
<s-breadcrumb :caminhos="{{$breadcrumb}}"></s-breadcrumb>
<form action="{{ route('grupos.store') }}" method="POST">
    {{ csrf_field() }}
    <s-criargrupo link="http://projetosara.meu/master/permissoes" old="{{old('grupo')}}">
        <button  type="submit" class="btn btn-outline-success"> <i class="fas fa-save"></i> Salvar </button>
        <button type="reset" class="btn btn-outline-secondary"><i class="fas fa-eraser"></i> Limpar</button>
    </s-criargrupo>
</form>
@if ($errors->has('grupo'))
    <s-snackbar cor="red" msg="{{ $errors->first('grupo') }}"></s-snackbar>
@elseif ($errors->has('idTelas'))
    <s-snackbar cor="red" msg="Selecione pelo menos uma permissão para poder continuar"></s-snackbar>
@endif
@endsection
