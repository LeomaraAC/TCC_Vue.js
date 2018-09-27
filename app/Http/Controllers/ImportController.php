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
        if($str != '')
            return (int) $str;
        return $str;
    }
    private function toFloat($valor) {
        $valor = $valor == '-' ? 0 : str_replace('.', '',  $valor);
        $valor = str_replace(',', '.',  $valor);
        return (float) $valor;
    }
    private function arrayAluno($value) {
        $value->sexo = strtoupper($value->sexo);
        $aluno = [
                'cpf' => $value->cpf,
                'rg' => $value->rg,
                'nome' => mb_convert_case($value->nome, MB_CASE_TITLE, "UTF-8"),
                'data_nascimento' => $value->data_de_nascimento,
                'nome_mae' => $value->nome_da_mae == '-' ? null : mb_convert_case($value->nome_da_mae, MB_CASE_TITLE, "UTF-8"),
                'nome_pai' => $value->nome_do_pai == '-' ? null : mb_convert_case($value->nome_do_pai, MB_CASE_TITLE, "UTF-8"),
                'sexo' => $value->sexo,
                'responsavel' => $value->responsavel == '-' ? null : mb_convert_case($value->responsavel, MB_CASE_TITLE, "UTF-8"),
                'email_pessoal' => $value->email_pessoal == '-' ? null : $value->email_pessoal,
                'email_responsavel' => $value->email_do_responsavel == '-' ? null : $value->email_do_responsavel,
                'estado_civil' => $value->estado_civil == '-' ? 'Não Informado' : mb_convert_case($value->estado_civil, MB_CASE_TITLE, "UTF-8"),
                'naturalidade' => $value->naturalidade == '-' ? null : mb_convert_case($value->naturalidade, MB_CASE_TITLE, "UTF-8"),
                'deficiencia' => $value->deficiencia == '-' ? null : mb_convert_case($value->deficiencia, MB_CASE_TITLE, "UTF-8"),
                'etnia' => $value->etniaraca == '-' ? 'Não declarado' : mb_convert_case($value->etniaraca, MB_CASE_TITLE, "UTF-8"),
                'necessidades_especiais' => $value->necessidade_especial == '-' ? null : mb_convert_case($value->necessidade_especial, MB_CASE_TITLE, "UTF-8"),
                'renda_bruta' => $this->toFloat($value->renda_bruta_familiar_r),
                'renda_per_capta' => $this->toFloat($value['renda_per_capta_qtd._salarios']),
                'superdotacao' => $value->superdotacao == '-' ? null : mb_convert_case($value->superdotacao, MB_CASE_TITLE, "UTF-8"),
                'tipo_escola_origem' => $value->tipo_de_escola_de_origem == '-' ? null : mb_convert_case($value->tipo_de_escola_de_origem, MB_CASE_TITLE, "UTF-8"),
                'transtorno' => $value->transtorno == '-' ? null : mb_convert_case($value->transtorno, MB_CASE_TITLE, "UTF-8"),
                'endereco' => mb_convert_case($value->endereco, MB_CASE_TITLE, "UTF-8"),
            ];
        return $aluno;
    }
    private function arrayMatricula($value) {
        $arrCurso = explode('-', $value->curso);
        if($value->data_de_integralizacao != '-'){
            $data = explode(' ', $value->data_de_integralizacao);
            $value->data_de_integralizacao = $data[0];
        }
        $matricula = [
                        'prontuario' => $value->matricula,
                        'codigo_curso' => trim($arrCurso[0]),
                        'previsao_conclusao' => $value->ano_letivo_de_previsao_de_conclusao,
                        'ano_ingresso' => $value->ano_de_ingresso,
                        'data_integralizacao' => $value->data_de_integralizacao == '-' ? null : $value->data_de_integralizacao,
                        'forma_ingresso' => mb_convert_case($value->forma_de_ingresso, MB_CASE_TITLE, "UTF-8"),
                        'instituicao_anterior' => $value->instituicao_de_ensino_anterior == '-' ? null : mb_convert_case($value->instituicao_de_ensino_anterior, MB_CASE_TITLE, "UTF-8"),
                        'situacao_curso' => mb_convert_case($value->situacao_no_curso, MB_CASE_TITLE, "UTF-8"),
                        'situacao_periodo' => $value->situacao_no_periodo == '-' ? null : mb_convert_case($value->situacao_no_periodo, MB_CASE_TITLE, "UTF-8"),
                        'turma' => $value->turma == '-' ? null : mb_convert_case($value->turma, MB_CASE_TITLE, "UTF-8"),
                        'email_academico' => $value->email_academico == '-' ? null : mb_convert_case($value->email_academico, MB_CASE_TITLE, "UTF-8"),
                        'observacao_historico' => $value->observacao_historico == '-' ? null : mb_convert_case($value->observacao_historico, MB_CASE_TITLE, "UTF-8"),
                        'Observacoes' => $value->observacoes == '-' ? null : mb_convert_case($value->observacoes, MB_CASE_TITLE, "UTF-8"),
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
                'descricao' => mb_convert_case($arrCurso[1], MB_CASE_TITLE, "UTF-8")
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
                //Verificando se a classe é a correspondente a linha
                //Caso a classe não seja, significa que existe duas planilhas
                if (get_class($data) == 'Maatwebsite\Excel\Collections\RowCollection') {
                    $sem_cpf = 0;
                    foreach ($data as $key => $value) {
                        //Procurar o aluno.Se ele existir os dados devem ser atualiizados, 
                        //se não devem ser criados
                        $value->cpf = $this->soNumeros($value->cpf);
                        if($value->cpf == '') {
                            $sem_cpf++;
                            $value->cpf = $sem_cpf;
                        }

                        $dadosMatricula = $this->arrayMatricula($value);
                        //Criar o curso caso não exista
                        $this->salvarCurso($value);
                        $findAluno = $this->alunoRepo->find($value->cpf);
                        if($findAluno){
                            //Atualizar os registros quando o usuário já existir
                    
                            //Atualizar o aluno
                            $dadosAluno = $this->arrayAluno($value);
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


                            // Salvando o aluno
                            $dadosAluno = $this->arrayAluno($value);
                            $this->alunoRepo->create($dadosAluno);                       
                
                            // Salvando o Telefone
                            $this->salvarTelefones($value);
                            
                            // Salvando a matricula
                            $this->matriculaRepo->create($dadosMatricula);

                        }
                    }
                    if($sem_cpf != 0)
                        return back()->with('error', $sem_cpf.' alunos foram importados com cpf inválido.');
                    else
                        return back()->with('success', 'Todos os dados importados com sucesso.');
                } else {
                    return back()->with('error', 'Erro ao importar os dados. Arquivo com duas planilhas.');
                }
            }
            else
                return back()->with('error', 'Erro ao importar os dados. Planilha vazia.');
        }
        else
            return back()->with('error', 'Erro ao importar os dados.');
    }
}
