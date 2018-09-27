<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Kaushan+Script');
        .font{
            font-family: 'Kaushan Script', cursive;
            color: green;
        }
        hr {
            margin-top: -5px;
        }
        h6 {
            color: green;
        }
    </style>  
  </head>
  <body>
    <div class="row">
        <h3><span class="font">SARA</span><small> Campus Capivari</small></h3>
        <div class="text-center">
            <strong>{{$aluno->nome}}</strong>
        </div>
    </div>
    <h6>Dados do Pessoais</h6>
    <hr>
    <strong>RG:</strong> {{$aluno->rg}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>CPF:</strong> {{$aluno->cpf}}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Data de nascimento:</strong> {{$aluno->data_nascimento}} 
    <br>
    <strong>Sexo:</strong> {{$aluno->sexo}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Estado Civil:</strong> {{$aluno->estado_civil}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Naturalidade:</strong> {{$aluno->naturalidade}}
    <br>
    <strong>Etnia\Raça:</strong>{{$aluno->etnia}}
    @if($aluno->deficiencia != '' || $aluno->necessidades_especiais != '' || $aluno->superdotacao != '' || $aluno->transtorno != '')
        <br>
    @endif

    @if($aluno->deficiencia != '')
        <strong>Deficiência:</strong> {{$aluno->deficiencia}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @endif
    @if($aluno->necessidades_especiais != '')
        <strong>Necessidade Especial:</strong> {{$aluno->necessidades_especiais}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @endif
    @if($aluno->superdotacao != '')
        <strong>Superdotação:</strong> {{$aluno->superdotacao}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @endif

    @if($aluno->transtorno != '')
        <strong>Transtorno:</strong> {{$aluno->transtorno}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    @endif

    <br><br><br>
    <h6><strong>Filiação</strong></h6>
    <hr>
    <strong>Nome da mãe:</strong> {{$aluno->nome_mae}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Nome do pai:</strong> {{$aluno->nome_pai}}  
    @if($aluno->responsavel != '')
        <strong>Responsável:</strong> {{$aluno->responsavel}}
        <br>
    @endif

    <br><br><br>
    <h6><strong>Endereço</strong></h6>
    <hr>
    {{$aluno->endereco}}
    <br><br><br>
    <h6><strong>Contato</strong></h6>
    <hr>
    <strong>E-mail Pessoal:</strong> {{$aluno->email_pessoal}}  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Telefones:</strong>
    @foreach ($aluno->telefone as $tel)
        {{ $tel->numero }},
    @endforeach
    <strong>-</strong>
    @if($aluno->email_responsavel != '')
        <br>
        <strong>E-mail do Responsável:</strong> {{$aluno->email_responsavel}}
    @endif
    <br>
    <br><br><br>
    <h6><strong>Situação Socioeconômica</strong></h6>
    <hr>
    <strong>Renda Bruta:</strong> @php echo number_format($aluno->renda_bruta, 2, ',', '.');  @endphp &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <strong>Renda per capita:</strong> @php echo number_format($aluno->renda_per_capta, 2, ',', '.');  @endphp
    <br><br><br>
    <h6><strong>Dados Educacionais</strong></h6>
    <hr>
    <strong>Tipo da escola de origem:</strong> {{$aluno->tipo_escola_origem}}
    <br><br>
    @foreach($matriculas as $matricula)
        <center>
            <br>
            <strong>Curso:</strong> {{$matricula->codigo_curso}} - {{$matricula->curso->descricao}}
            <br>
            <strong>Ano de entrada:</strong> {{$matricula->ano_ingresso}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Ano previsto para conclusão:</strong> {{$matricula->previsao_conclusao}}
            <br>
            <strong>E-mail do Acadêmico:</strong> 
            @if($matricula->email_academico != '')
                {{$matricula->email_academico}}
            @else
                -
            @endif
        </center>
        <br>
        <strong>Data de integralização:</strong> 
        @if($matricula->data_integralizacao != '')
            {{$matricula->data_integralizacao}}
        @else
            -
        @endif
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <strong>Forma de Ingresso:</strong> {{$matricula->forma_ingresso}} 
        <br>
        <strong>Situação no curso:</strong> {{$matricula->situacao_curso}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>Situação no período:</strong> {{$matricula->situacao_periodo}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>Turma:</strong> 
        @if($matricula->turma != '')
            {{$matricula->turma}}
        @else
            -
        @endif
        <br>
        <strong>Instituição de ensino anterior:</strong> 
        @if($matricula->instituicao_anterior != '')
            {{$matricula->instituicao_anterior}}
        @else
            -
        @endif
        <br>
        <strong>Observações histórico:</strong> 
        @if($matricula->observacao_historico != '')
            {{$matricula->observacao_historico}}
        @else
            -
        @endif
        <br>
        <strong>Observações gerais:</strong> 
        @if($matricula->observacoes != '')
            {{$matricula->observacoes}}
        @else
            -
        @endif
        <br>
        <br> <br>
    @endforeach
  </body>
</html>