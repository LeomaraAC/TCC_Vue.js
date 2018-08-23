<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use App\Aluno;
use App\Repositories\AlunoRepository;

class AlunosTest extends TestCase
{
    protected $repo;
    protected $alunoAuxiliar;
    // protected $alunoEditar;
    // protected $alunoExcluir;
    protected $alunoInserido;

    public function setUp() {
        parent::setUp();
        $this->repo = new AlunoRepository(new Aluno());
        $this->alunoAuxiliar = $this->createAlunoFactory();
        $this->alunoInserido['idAluno'] = -1;
        
    }
    public function tearDown() {
        Aluno::where('idAluno', '=', $this->alunoAuxiliar['idAluno'])->forceDelete();
        if($this->alunoInserido['idAluno'] != -1)
            Aluno::where('idAluno', '=', $this->alunoInserido['idAluno'])->forceDelete();
        parent::tearDown();
    }

    protected function createAlunoFactory()
    {
        $aluno = factory(\App\Aluno::class)->create()->toArray();
        $aluno['curso'] = $aluno["idCurso"];
        unset($aluno["idCurso"]);        
        $aluno['semestre'] = $aluno["semestreAno"];
        unset($aluno["semestreAno"]);
        return $aluno;
    }

    protected function getAlunoFactory()
    {
        $aluno = factory(\App\Aluno::class)->make()->toArray();
        $aluno['motivoCancelamento'] = '';
        $aluno['curso'] = $aluno["idCurso"];
        unset($aluno["idCurso"]);        
        $aluno['semestre'] = $aluno["semestreAno"];
        unset($aluno["semestreAno"]);        
        return $aluno;
    }
    protected function findAlunoId($id) {
        return Aluno::find($id);
    }

    protected function inserir_dados(array $dados) {
        $request = new \Illuminate\Http\Request($dados);
        $dado_salvo = $this->repo->inserirAluno($request);
        return $dado_salvo;
    }

    protected function editar_dados(array $dados, int $id) {
        $request = new \Illuminate\Http\Request($dados);
        $update = $this->repo->atualizarAluno($request, $id);
        return $update;
    }
    // ****************************************************************************
    // *****************************Inclusão de alunos*****************************
    // ****************************************************************************

    /**
     * Prontuário
     */
    public function teste_inserir_aluno_sem_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['prontuario'] = '';
        $this->inserir_dados($novo_aluno);
    }

    public function teste_inserir_aluno_prontuario_caract_espec() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['prontuario'] = 'cv12345*';
        $this->inserir_dados($novo_aluno);
    }

    public function teste_inserir_aluno_maximo_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['prontuario'] = 'cv1234567890';
        $this->inserir_dados($novo_aluno);
    }

    public function teste_inserir_aluno_prontuario_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['prontuario'] = $this->alunoAuxiliar['prontuario'];
        $this->inserir_dados($novo_aluno);
    }
    /**
     * Nome
     */
    public function teste_inserir_aluno_sem_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['nome'] = '';
        $this->inserir_dados($novo_aluno);
    }

    public function teste_inserir_aluno_maximo_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['nome'] = 'kkkkkkkkkkpopopopopopopopttttttttopopopopopopopopopopopopopopppoookokjjkkjjj';
        $this->inserir_dados($novo_aluno);
    }
    /**
     * E-mail
     */
    public function teste_inserir_aluno_sem_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['email'] = '';
        $this->inserir_dados($novo_aluno);
    }

    public function teste_inserir_aluno_email_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['email'] = $this->alunoAuxiliar['email'];
        $this->inserir_dados($novo_aluno);
    }

    /**
     *Telefone 
     */
    public function teste_inserir_aluno_maximo_telefone() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['telefone'] = '123456789012345678901234567890';
        $this->inserir_dados($novo_aluno);
    }

    /**
     *Semestre 
     */
    public function teste_inserir_aluno_sem_semestre() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['semestre'] = '';
        $this->inserir_dados($novo_aluno);
    }
        
    /**
     *Sem Curso 
     */
    public function teste_inserir_aluno_sem_curso() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_aluno = $this->getAlunoFactory();
        $novo_aluno['curso'] = '';
        $this->inserir_dados($novo_aluno);
    }

    /**
     *  Inserir aluno com todos os dados
     */
     public function teste_inserir_aluno() {
        $novo_aluno = $this->getAlunoFactory();    
       $this->alunoInserido = $this->inserir_dados($novo_aluno);
        
        $this->assertInstanceOf(\App\Aluno::class, $this->alunoInserido);
        $this->assertDatabaseHas('alunos', [
            'prontuario' => $novo_aluno['prontuario'],
            'nome' => $novo_aluno['nome'],
            'email' => $novo_aluno['email'],
            'semestreAno' => $novo_aluno['semestre']
        ]);
    }

    
    // ****************************************************************************
    // ********************************Editar alunos*******************************
    // ****************************************************************************

    /**
     * Prontuário
     */
    public function teste_editar_aluno_sem_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['prontuario'] = '';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    public function teste_editar_aluno_prontuario_caract_espec() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['prontuario'] = 'cv12345*';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    public function teste_editar_aluno_maximo_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['prontuario'] = 'cv1234567890';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    public function teste_editar_aluno_prontuario_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoInserido = $this->createAlunoFactory();
        $this->alunoAuxiliar['prontuario'] = $this->alunoInserido['prontuario'];
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }
    /**
     * Nome
     */
    public function teste_editar_aluno_sem_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['nome'] = '';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    public function teste_editar_aluno_maximo_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['nome'] = 'kkkkkkkkkkpopopopopopopopttttttttopopopopopopopopopopopopopopppoookokjjkkjjj';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }
    /**
     * E-mail
     */
    public function teste_editar_aluno_sem_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['email'] = '';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    public function teste_editar_aluno_email_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoInserido = $this->createAlunoFactory();
        $this->alunoAuxiliar['email'] = $this->alunoInserido['email'];
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    /**
     *Telefone 
     */
    public function teste_editar_aluno_maximo_telefone() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['telefone'] = '123456789012345678901234567890';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    /**
     *Semestre 
     */
    public function teste_editar_aluno_sem_semestre() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['semestre'] = '';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }
        
    /**
     *Sem Curso 
     */
    public function teste_editar_aluno_sem_curso() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->alunoAuxiliar['curso'] = '';
        $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
    }

    /**
     *  Inserir aluno com todos os dados
     */
     public function teste_editar_aluno() {
        $aluno_atualizado = $this->getAlunoFactory();
        $this->alunoAuxiliar['semestre'] = $aluno_atualizado['semestre'];
        $this->alunoAuxiliar['telefone'] = $aluno_atualizado['telefone'];
        $this->alunoAuxiliar['emil'] = $aluno_atualizado['email'];
        
        $update = $this->editar_dados($this->alunoAuxiliar, $this->alunoAuxiliar['idAluno']);
        $aluno_editado = $this->findAlunoId($this->alunoAuxiliar['idAluno']);
        
        $this->assertTrue($update);
        $this->assertEquals($this->alunoAuxiliar['telefone'],$aluno_editado['telefone']);
        $this->assertEquals($this->alunoAuxiliar['nome'],$aluno_editado['nome']);

        $this->assertDatabaseHas('alunos', [
            'prontuario' => $this->alunoAuxiliar['prontuario'],
            'nome' => $this->alunoAuxiliar['nome'],
            'email' => $this->alunoAuxiliar['email'],
            'semestreAno' => $this->alunoAuxiliar['semestre']
        ]);
    }

    // ****************************************************************************
    // ********************************Listar alunos*******************************
    // ****************************************************************************

    public function teste_listar_alunos_sem_parametro() {
        $page = $this->repo->filtro();
        $filtro = $page->toArray();

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $this->assertEquals( 1,$filtro['current_page']);
        $this->assertArrayHasKey('per_page',$filtro, 'ERRO... Não contem a chave com o num de páginas');  
    }

    public function teste_listar_alunos_com_filtro() {
        $page = $this->repo->filtro('idAluno', 'asc',  substr($this->alunoAuxiliar['nome'], 0, 2));
        $filtro = $page->toArray();

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $this->assertNotCount(0, $filtro['data']);
    }

    // ****************************************************************************
    // ********************************Excluir alunos******************************
    // ****************************************************************************
    public function teste_apagar_aluno_safe_delete() {
        $this->alunoAuxiliar = $this->findAlunoId($this->alunoAuxiliar['idAluno'])->toArray();
        $delete = $this->repo->delete($this->alunoAuxiliar['idAluno']);
        $this->assertTrue($delete);
        $this->assertSoftDeleted('alunos', $this->alunoAuxiliar);
    }

    public function teste_apagar_aluno_id_invalido() {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $delete = $this->repo->delete(2222222222222222222222222222222);
    }
}
