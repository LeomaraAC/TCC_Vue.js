<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

use App\User;
use App\Repositories\UserRepository;

class UsersTest extends TestCase
{
    use WithFaker;
    protected $userRepository;

    public function setUp() {
        $this->userRepository = new UserRepository(new User());
        parent::setUp();
    }
    
    protected function createUserFactory()
    {
        $usuario = factory(\App\User::class)->create()->toArray();
        return $usuario;
    }

    protected function getUserFactory()
    {
        $usuario = factory(\App\User::class)->make()->toArray();
        $usuario['senha'] = 'secreto';
        $usuario['senha_confirmation'] = 'secreto';
        $usuario['grupos'] = $usuario["idGrupo"];
        unset($usuario["idGrupo"]);        
        return $usuario;
    }

    protected function findUserId($id) {
        return User::find($id);
    }

    protected function inserir_dados(array $dados) {
        $request = new \Illuminate\Http\Request($dados);
        $dado_salvo = $this->userRepository->salvarUsuario($request);
        return $dado_salvo;
    }

    protected function editar_dados(array $dados, int $id) {
        $request = new \Illuminate\Http\Request($dados);
        $update = $this->userRepository->atualizaUsuario($request, $id);
        return $update;
    }

    /*
    =============================Incluir==================================
    */
    /**
     * Teste para incluir usuário
     */
    public function teste_inserir_usuario() {
        $novo_usuario = $this->getUserFactory();     
       $usuario_salvo = $this->inserir_dados($novo_usuario);
        
        $this->assertInstanceOf(\App\User::class, $usuario_salvo);
        $usuario_salvo = $usuario_salvo->toArray();
        $this->assertEquals($novo_usuario['nome'], $usuario_salvo['nome']);
        $this->assertEquals($novo_usuario['prontuario'], $usuario_salvo['prontuario']);
        $this->assertEquals($novo_usuario['email'], $usuario_salvo['email']);
        $this->assertEquals($novo_usuario['grupos'], $usuario_salvo['idGrupo']);
    }

    public function teste_inserir_usuario_sem_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['nome'] = '';
       $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_maximo_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['nome'] = 'kkkkkkkkkkpopopopopopopopopopopopopopopopopopopopopopppoookokjjkkjjj';
       $usuario_salvo = $this->inserir_dados($novo_usuario);
    }
    public function teste_inserir_usuario_sem_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['prontuario'] = '';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_prontuario_caract_espec() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['prontuario'] = 'cv12345*';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_maximo_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['prontuario'] = 'cv1234567890';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_prontuario_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $usuario_existente = $this->createUserFactory();

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['prontuario'] = $usuario_existente['prontuario'];
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_email_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $usuario_existente = $this->createUserFactory();

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['email'] = $usuario_existente['email'];
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_sem_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['email'] = '';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_sem_tipo_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['email'] = 'adscvmsd';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_sem_senha() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['senha'] = '';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_sem_confirmar_senha() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['senha_confirmation'] = 'p@ssw0rd';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_minimo_senha() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['senha'] = 'abcd';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_sem_grupo() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $novo_usuario = $this->getUserFactory();
        $novo_usuario['grupos'] = '';
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    /*
    =============================Editar==================================
    */

    public function teste_editar_todos_dados_usuario () {
        $old_usuario = $this->createUserFactory();
        $usuario_atualizado = $this->getUserFactory();
        
        $update = $this->editar_dados($usuario_atualizado,$old_usuario['idUser']);
        $usuario_salvo = $this->findUserId($old_usuario['idUser']);
        
        $this->assertTrue($update);
        $this->assertNotEquals($old_usuario['nome'],$usuario_salvo['nome']);
    }

    public function teste_editar_usuario_sem_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['nome'] = '';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_maximo_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['nome'] = 'kkkkkkkkkkpopopopopopopopopopopopopopopopopopopopopopppoookokjjkkjjj';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_sem_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['prontuario'] = '';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_prontuario_caract_espec() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['prontuario'] = 'cv12345*';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_maximo_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['prontuario'] = 'cv1234567890';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_prontuario_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $usuario_existente = $this->createUserFactory();

        $old_usuario = $this->createUserFactory();
        $old_usuario['prontuario'] = $usuario_existente['prontuario'];
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_email_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $usuario_existente = $this->createUserFactory();

        $old_usuario = $this->createUserFactory();
        $old_usuario['email'] = $usuario_existente['email'];
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_sem_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['email'] = '';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_sem_tipo_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['email'] = 'adscvmsd';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    public function teste_editar_usuario_sem_grupo() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $old_usuario = $this->createUserFactory();
        $old_usuario['grupos'] = '';
        $usuario_salvo = $this->editar_dados($old_usuario,$old_usuario['idUser']);
    }

    /*
    =============================Listar==================================
    */

    public function teste_listar_usuarios_sem_parametro() {
        $page = $this->userRepository->filtro();
        $filtro = $page->toArray();

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $this->assertEquals( 1,$filtro['current_page']);
        $this->assertArrayHasKey('per_page',$filtro, 'ERRO... Não contem a chave com o num de páginas');  
    }

    public function teste_listar_usuarios_com_filtro() {
        $novo_usuario = $this->createUserFactory();
        $page = $this->userRepository->filtro('idUser', 'asc',  substr($novo_usuario['nome'], 0, 2));
        $filtro = $page->toArray();

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $this->assertNotCount(0, $filtro['data']);
    }

    /*
    =============================Deletar==================================
    */

    public function teste_apagar_usuarios_safe_delete() {
        $novo_usuario = $this->createUserFactory();
        $delete = $this->userRepository->delete($novo_usuario['idUser']);
        $this->assertTrue($delete);
        $this->assertSoftDeleted('users', $novo_usuario);
    }

    public function teste_apagar_usuarios_id_invalido() {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $novo_usuario = $this->createUserFactory();
        $delete = $this->userRepository->delete(2222222222222222222222222222222);
    }
}
