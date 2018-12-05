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
    <h6>Atendimentos</h6>
    <hr>
    @if($atendimentosAluno == [])
        <strong>Esse aluno não possui nenhum atendimento!</strong>
    @else
        @foreach($atendimentosAluno as $atendimento)
            <strong>Curso: </strong> {{$atendimento['curso']}}
            <br>
            <strong>Data: </strong>{{$atendimento['agendamento']->data}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Hora: </strong>{{$atendimento['agendamento']->hora}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Tipo: </strong>{{$atendimento['agendamento']->descricao}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Atendimento: </strong>{{$atendimento['agendamento']->formaAtendimento}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <br>
            <strong>Status: </strong>{{$atendimento['agendamento']->status}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @if($atendimento['agendamento']->status == "Remarcada")
            <strong>Atendimento remarcado para: </strong> {{$atendimento['agendamento']->dataRemarcada}}
            @endif
            @if($atendimento['agendamento']->status == "Realizada")
            <strong>Data Realizado: </strong> {{$atendimento['atendimento']->dataRealizado}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong>Horário Realizado: </strong> {{$atendimento['atendimento']->horaRealizado}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <br>
            <strong>Responsáveis pelo atendimento: </strong> {{$atendimento['atendimento']->nome}}
            @if($atendimento['agendamento']->formaAtendimento == "Familiar")
            <br>
            <strong>Comparecimento familiar: </strong> {{$atendimento['atendimento']->comparecimentoFamiliar}} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            @endif
            @if($atendimento['atendimento']->comparecimentoFamiliar == "Sim")
                <strong>Grau de Parentesco: </strong> {{$atendimento['atendimento']->grauParentesco}}
            @endif
            <br>
            <strong>Resumo: </strong> {{$atendimento['atendimento']->resumo}}
            @endif
            <br><br><br>
        @endforeach
    @endif
  </body>
</html>