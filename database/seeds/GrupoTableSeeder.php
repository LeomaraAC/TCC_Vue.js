<?php

use Illuminate\Database\Seeder;

class GrupoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Grupo::class,50)->create()->each(function($a) {
            $a->funcoes()->attach(App\Permissao::all()->random(3));
        });
    }
}
