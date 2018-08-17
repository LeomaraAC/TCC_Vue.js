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
    protected $usuarioAuxiliar;
    protected $usuarioEditar;
    protected $usuarioExcluir;
    protected $usuarioInserido;
    protected $usuario;

    
    public function setUp() {
        parent::setUp();
        $this->userRepository = new UserRepository(new User());
        $this->usuarioAuxiliar = $this->createUserFactory();
        $this->usuarioEditar = $this->createUserFactory();
        $this->usuarioExcluir = $this->createUserFactory();
        $this->usuarioInserido['idUser'] = -1;
        
    }
    public function tearDown() {
        $this->findUserId($this->usuarioAuxiliar['idUser'])->forceDelete();
        $this->findUserId($this->usuarioEditar['idUser'])->forceDelete();
        User::where('idUser', '=', $this->usuarioExcluir['idUser'])->forceDelete();
        if($this->usuarioInserido['idUser'] != -1)
            $this->findUserId($this->usuarioInserido['idUser'])->forceDelete();
        parent::tearDown();
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
       $this->usuarioInserido = $this->inserir_dados($novo_usuario);
        
        $this->assertInstanceOf(\App\User::class, $this->usuarioInserido);
        $usuario_salvo = $this->usuarioInserido->toArray();
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
        $novo_usuario = $this->getUserFactory();
        $novo_usuario['prontuario'] = $this->usuarioAuxiliar['prontuario'];
        $usuario_salvo = $this->inserir_dados($novo_usuario);
    }

    public function teste_inserir_usuario_email_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $novo_usuario = $this->getUserFactory();
        $novo_usuario['email'] = $this->usuarioAuxiliar['email'];
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
        $usuario_atualizado = $this->getUserFactory();
        
        $update = $this->editar_dados($usuario_atualizado,$this->usuarioEditar['idUser']);
        $usuario_salvo = $this->findUserId($this->usuarioEditar['idUser']);
        
        $this->assertTrue($update);
        $this->assertNotEquals($this->usuarioEditar['nome'],$usuario_salvo['nome']);
    }

    public function teste_editar_usuario_sem_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['nome'] = '';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_maximo_nome() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['nome'] = 'kkkkkkkkkkpopopopopopopopopopopopopopopopopopopopopopppoookokjjkkjjj';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_sem_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['prontuario'] = '';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_prontuario_caract_espec() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['prontuario'] = 'cv12345*';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_maximo_prontuario() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['prontuario'] = 'cv1234567890';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_prontuario_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['prontuario'] = $this->usuarioAuxiliar['prontuario'];
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_email_repetido() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['email'] = $this->usuarioAuxiliar['email'];
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_sem_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['email'] = '';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_sem_tipo_email() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['email'] = 'adscvmsd';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
    }

    public function teste_editar_usuario_sem_grupo() {
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $this->usuarioEditar['grupos'] = '';
        $usuario_salvo = $this->editar_dados($this->usuarioEditar,$this->usuarioEditar['idUser']);
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
        $page = $this->userRepository->filtro('idUser', 'asc',  substr($this->usuarioAuxiliar['nome'], 0, 2));
        $filtro = $page->toArray();

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $this->assertNotCount(0, $filtro['data']);
    }

    /*
    =============================Deletar==================================
    */

    public function teste_apagar_usuarios_safe_delete() {
        $delete = $this->userRepository->delete($this->usuarioExcluir['idUser']);
        $this->assertTrue($delete);
        $this->assertSoftDeleted('users', $this->usuarioExcluir);
    }

    public function teste_apagar_usuarios_id_invalido() {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $delete = $this->userRepository->delete(2222222222222222222222222222222);
    }
}
