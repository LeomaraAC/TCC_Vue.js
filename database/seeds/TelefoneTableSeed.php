<?php

use Illuminate\Database\Seeder;

class TelefoneTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Telefone::insert([
            [
                'numero' => '(19) 3816-5885',
                'idAluno' => 1
            ],
            [
                'numero' => '(19) 99371-5066',
                'idAluno' => 1
            ],
            [
                'numero' => '(19) 2826-5574',
                'idAluno' => 3
            ],
            [
                'numero' => '(19) 99384-9733',
                'idAluno' => 4
            ],
            [
                'numero' => '(19) 99381-5388',
                'idAluno' => 4
            ]
        ]);
    }
}
