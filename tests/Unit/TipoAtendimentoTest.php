<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\TipoAtendimento;
use App\Repositories\TipoAtendimentoRepository;

class TipoAtendimentoTest extends TestCase
{
    use WithFaker;
    private $repo;
    private $tipoEditar;
    private $tipoIncluir;
    private $tipoApagar;
    private $tipoExistente;

    public function setUp() {
        parent::setUp();
        $this->repo = new TipoAtendimentoRepository(new TipoAtendimento());
        // Preparando a massa de dados inicial para os testes...
        $this->tipoEditar = $this->createTipoFactory();
        $this->tipoApagar = $this->createTipoFactory();
        $this->tipoExistente = $this->createTipoFactory();
        $this->tipoIncluir['idTipo_atendimento'] = -1;
        
    }

    public function tearDown() {
        //Apagando os dados inseridos no banco antes e durante a realização dos testes...
        $this->findTipoId($this->tipoEditar['idTipo_atendimento'])->forceDelete();
        $this->findTipoId($this->tipoExistente['idTipo_atendimento'])->forceDelete();
        TipoAtendimento::where('idTipo_atendimento', '=', $this->tipoApagar['idTipo_atendimento'])->forceDelete();
        if($this->tipoIncluir['idTipo_atendimento'] != -1)
            $this->findTipoId($this->tipoIncluir['idTipo_atendimento'])->forceDelete();
        parent::tearDown();
    }

    private function createTipoFactory(){
        $tipo = factory(\App\TipoAtendimento::class)->create()->toArray();
        return $tipo;
    }

    private function getTipoFactory(){
        $tipo = factory(\App\TipoAtendimento::class)->make()->toArray();
        return $tipo;
    }

    private function findTipoId($id) {
        return TipoAtendimento::find($id);
    }

    private function returnRequest(array $dados) {
        $request = new \Illuminate\Http\Request($dados);
        return $request;
    }

    /*
    =============================Incluir==================================
    */
    public function testeInserirTipo() {
        $tipo = $this->getTipoFactory();
        $request = $this->returnRequest($tipo);
        $this->tipoIncluir = $this->repo->salvarTipo($request);

        $this->assertInstanceOf(\App\TipoAtendimento::class, $this->tipoIncluir);
        $this->tipoIncluir = $this->tipoIncluir->toArray();
        $this->assertEquals($tipo['descricao'], $this->tipoIncluir['descricao']);
    }

    public function testeInserirTipoDescricaoRepetido() {
        // Espera-se uma exception...
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $tipo = $this->getTipoFactory();
        $tipo['descricao'] = $this->tipoExistente['descricao'];

        $request = $this->returnRequest($tipo);
        $this->tipoIncluir = $this->repo->salvarTipo($request);
    }

    public function testeInserirTipoDescricaoVazio() {
        // Espera-se uma exception...
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $tipo = $this->getTipoFactory();
        $tipo['descricao'] = '';

        $request = $this->returnRequest($tipo);
        $this->tipoIncluir = $this->repo->salvarTipo($request);
    }

    public function testeInserirTipoDescricaoMinimo() {
        // Espera-se uma exception...
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $tipo = $this->getTipoFactory();
        $tipo['descricao'] = 'oi';

        $request = $this->returnRequest($tipo);
        $this->tipoIncluir = $this->repo->salvarTipo($request);
    }

    public function testeInserirTipoDescricaoMaximo() {
        // Espera-se uma exception...
        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $tipo = $this->getTipoFactory();
        $tipo['descricao'] = 'zxcvbnmlkjhgfdsaqwertyuiopçmnbvcxzasdfghjklçpoiuytrewqqwertyuiopçlkjhgfdsazxcvbnmçlkjhgfdsaqwertyuiopçlkjhgfdsazxcvbnm';

        $request = $this->returnRequest($tipo);
        $this->tipoIncluir = $this->repo->salvarTipo($request);
    }

    /*
    =============================Listar==================================
    */

    public function testeListarTipoSemParametro() {
        $page = $this->repo->filtro();

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $page = $page->toArray();
        $this->assertEquals( 1,$page['current_page']);
        $this->assertArrayHasKey('per_page',$page, 'ERRO... Não contem a chave com o número de páginas');  
    }

    public function testeListarTipoComParametro() {
        $page = $this->repo->filtro('idTipo_atendimento', 'desc',  substr($this->tipoExistente['descricao'], 0, 2));

        $this->assertInstanceOf(LengthAwarePaginator::class, $page);
        $page = $page->toArray();
        $this->assertNotCount(0, $page['data']);
    }

    /*
    =============================Deletar==================================
    */

    public function testeApagarTipoSafeDelete() {
        $delete = $this->repo->delete($this->tipoApagar['idTipo_atendimento']);
        $this->assertTrue($delete);
        $this->assertSoftDeleted('tipo_atendimento', $this->tipoApagar);
    }

    public function testeApagarTipoIdInvalido() {
        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $delete = $this->repo->delete(-10);
    }
}
