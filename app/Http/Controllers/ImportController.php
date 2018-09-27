<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\AlunoRepository;
use App\Repositories\MatriculaRepository;
use App\Repositories\TelefoneRepository;
use App\Repositories\CursoRepository;
use DateTime;
use Excel;

class ImportController extends Controller
{
    protected $alunoRepo;
    protected $matriculaRepo;
    protected $telefoneRepo;
    protected $cursoRepo;

    public function __construct(AlunoRepository $alunoRepo,
                                MatriculaRepository $matriculaRepo,
                                TelefoneRepository $telefoneRepo,
                                CursoRepository $cursoRepo) {
        $this->alunoRepo = $alunoRepo;
        $this->matriculaRepo = $matriculaRepo;
        $this->telefoneRepo = $telefoneRepo;
        $this->cursoRepo = $cursoRepo;
    }

    private function soNumeros($str) {
        $str = preg_replace('/[^0-9]/i', '_', $str);
        $str = preg_replace('/_+/', '', $str);
        return $str;
    }
    }
        $aluno = [
                'cpf' => $value->cpf,
                'rg' => $value->rg,
                'nome' => $value->nome,
                'data_nascimento' => $value->data_de_nascimento,
                'nome_mae' => $value->nome_da_mae == '-' ? null : $value->nome_da_mae,
                'nome_pai' => $value->nome_do_pai == '-' ? null : $value->nome_do_pai,
                'sexo' => $value->sexo,
                'responsavel' => $value->responsavel == '-' ? null : $value->responsavel,
                'email_pessoal' => $value->email_pessoal == '-' ? null : $value->email_pessoal,
                'email_responsavel' => $value->email_do_responsavel == '-' ? null : $value->email_do_responsavel,
                'estado_civil' => $value->estado_civil == '-' ? 'Não Informado' : $value->estado_civil,
                'naturalidade' => $value->naturalidade == '-' ? null : $value->naturalidade,
                'deficiencia' => $value->deficiencia == '-' ? null : $value->deficiencia,
                'etnia' => $value->etniaraca == '-' ? 'Não declarado' : $value->etniaraca,
                'necessidades_especiais' => $value->necessidade_especial == '-' ? null : $value->necessidade_especial,
                'renda_bruta' => $value->renda_bruta_familiar_r == '-' ? 0 : (double) $value->renda_bruta_familiar_r,
                'renda_per_capta' => $value['renda_per_capta_qtd._salarios'] == '-' ? 0 : (double) $value['renda_per_capta_qtd._salarios'],
                'superdotacao' => $value->superdotacao == '-' ? null : $value->superdotacao,
                'escola_origem' => $value->instituicao_de_ensino_anterior == '-' ? null : $value->instituicao_de_ensino_anterior,
                'tipo_escola_origem' => $value->tipo_de_escola_de_origem == '-' ? null : $value->tipo_de_escola_de_origem,
                'transtorno' => $value->transtorno == '-' ? null : $value->transtorno,
                'idEndereco' => $endereco,
            ];
        return $aluno;
    }
    private function arrayMatricula($value) {
        $arrCurso = explode('-', $value->curso);
        if($value->data_de_integralizacao != '-'){
            $value->data_de_integralizacao = new DateTime($value->data_de_integralizacao);
        }
        $matricula = [
                        'prontuario' => $value->matricula,
                        'codigo_curso' => trim($arrCurso[0]),
                        'previsao_conclusao' => $value->ano_letivo_de_previsao_de_conclusao,
                        'ano_ingresso' => $value->ano_de_ingresso,
                        'data_integralizacao' => $value->data_de_integralizacao == '-' ? null : $value->data_de_integralizacao->format('Y-m-d'),
                        'forma_ingresso' => $value->forma_de_ingresso,
                        'instituicao_anterior' => $value->instituicao_de_ensino_anterior == '-' ? null : $value->instituicao_de_ensino_anterior,
                        'situacao_curso' => $value->situacao_no_curso,
                        'situacao_periodo' => $value->situacao_no_periodo == '-' ? null : $value->situacao_no_periodo,
                        'turma' => $value->turma == '-' ? null : $value->turma,
                        'email_academico' => $value->email_academico == '-' ? null : $value->email_academico,
                        'observacao_historico' => $value->observacao_historico == '-' ? null : $value->observacao_historico,
                        'Observacoes' => $value->observacoes == '-' ? null : $value->observacoes,
                        'cpf' => $value->cpf,
        ];
        return $matricula;
    }

    private function salvarTelefones($value) {
        $arrTelefone = explode(',', $value->telefone);
        foreach($arrTelefone as $telefone) {
            $telefone = $this->soNumeros($telefone);
            $this->telefoneRepo->create([
                'numero' => $telefone,
                'cpf' => $value->cpf
            ]);
        }
    }

    private function salvarCurso($value) {
        $arrCurso = explode('-', $value->curso);
        if(!$this->cursoRepo->find(trim($arrCurso[0]))){
            $this->cursoRepo->create([
                'codigo' => trim($arrCurso[0]),
                'descricao' => ucfirst($arrCurso[1])
            ]);
        }
    }

    public function importAlunos(Request $request) {

        if($request->hasFile('files')){
            // Recuperando o caminho do arquivo
            $path = $request->file('files')->getRealPath();
            // Recuperando os dados da planilha selecionada 
            $data = Excel::load($path)->get();
            if($data->count()){
                foreach ($data as $key => $value) {
                    //Procurar o aluno.Se ele existir os dados devem ser atualiizados, 
                    //se não devem ser criados
                    
                    
                    
                    $value->cpf = $this->soNumeros($value->cpf);
                    $dadosEndereco = $this->arrayEndereco($value);
                    $dadosMatricula = $this->arrayMatricula($value);
                    //Criar o curso caso não exista
                    $this->salvarCurso($value);
                    
                    $findAluno = $this->alunoRepo->find($value->cpf);
                    if($findAluno){
                        //Atualizar os registros quando o usuário já existir
                        
                        //Atualizar o endereço
                        $idEndereco = $findAluno->endereco->idEndereco;
                        $this->enderecoRepo->update($dadosEndereco, $idEndereco);

                        //Atualizar o aluno
                        $dadosAluno = $this->arrayAluno($value, $idEndereco );
                        $this->alunoRepo->update($dadosAluno, $findAluno->cpf); 

                        //Atualizar o telefone
                        //Apagar todos telefones que existe e...
                        $findAluno->telefone()->delete();
                        //inserir os novos.
                        $this->salvarTelefones($value);
                        
                        //Atualizar a matricula caso ela exista...
                        $findMatricula = $this->matriculaRepo->find($value->matricula);
                        if($findMatricula)
                            $this->matriculaRepo->update($dadosMatricula, $value->matricula);
                        else
                            $this->matriculaRepo->create($dadosMatricula);
                        
                        //Ou criar uma nova caso ela não exista...
                    }
                    else {
                        // Inserindo os registros do aluno pela primeira vez.

                        // Salvando o Endereço
                        $endereco = $this->enderecoRepo->create($dadosEndereco);

                        // Salvando o aluno
                        $dadosAluno = $this->arrayAluno($value, $endereco->idEndereco );
                        $this->alunoRepo->create($dadosAluno);                       
            
                        // Salvando o Telefone
                        $this->salvarTelefones($value);
                        
                        // Salvando a matricula
                        $this->matriculaRepo->create($dadosMatricula);

                    }
                }
                return back()->with('success', 'Dados importados com sucesso.');
            }
            else
                return back()->with('error', 'Erro ao importar os dados. Planilha vazia.');
        }
        else
            return back()->with('error', 'Erro ao importar os dados.');
    }
}
