<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Repositories\AlunoRepository;
use App\Repositories\AgendamentoRepository;
use App\Repositories\AtendimentoRepository;
use PDF;

class RelatoriosController extends Controller
{
    public function __construct(AlunoRepository $repoAluno, 
                                AtendimentoRepository $repoAtendimento,
                                AgendamentoRepository $repoAgendamento) {
        $this->repoAluno = $repoAluno;
        $this->repoAtendimento = $repoAtendimento;
        $this->repoAgendamento = $repoAgendamento;
    }

    public function alunosAtendimentos($id) {
        if(Gate::denies('relatorio_atendimento_alunos'))
            return redirect()->back()->with('error', 'Ops! Acesso negado');
        
        $aluno = $this->repoAluno->find($id);
        $atendimentosAluno = array();
        if($aluno)
        {
            foreach ($aluno->matricula as $matricula) {
                $agendamentos = $this->repoAgendamento->getAgendamentoByProntuario($matricula->prontuario);
                foreach($agendamentos as $a) {
                    $arr = array(
                        "curso" => $matricula->curso->descricao,
                        "agendamento" => null,
                        "atendimento" => null
                        );
                    $arr["agendamento"] = $a;
                    if($a->status == 'Realizada') {
                        $atendimento = $this->repoAtendimento->findByIdAgendamento($a->idAgendamento);
                        if($atendimento){
                            $arr["atendimento"] = $atendimento;
                        }
                    }
                    array_push($atendimentosAluno,$arr);
                }
            }
        }
        // dd($atendimentosAluno);
        $titulo = 'atendimentos_aluno_'.$aluno->cpf.'.pdf';
        return PDF::loadView('relatorios.alunos', compact('aluno','atendimentosAluno'))->stream($titulo);
    }
}
