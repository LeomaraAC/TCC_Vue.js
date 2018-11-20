<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissaoTableSeeder::class);
        $this->call(GrupoTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(TiposAtendimentoSeeder::class);
        $this->call(CursoTableSeed::class);
        $this->call(AlunosTableSeed::class);
        $this->call(TelefoneTableSeed::class);
        $this->call(MatriculaTableSeed::class);
        $this->call(AgendamentosTableSeed::class);
        // $this->call(CursoTableSeeder::class);
        // $this->call(AlunoTableSeeder::class);
    }
}
