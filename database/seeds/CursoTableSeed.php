<?php

use Illuminate\Database\Seeder;

class CursoTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Curso::insert([
            [
                'codigo' => 'CPV18047',
                'descricao' => 'TÉCNICO EM QUÍMICA (Câmpus Capivari)'
            ],
            [
                'codigo' => 'CPV18600',
                'descricao' => 'LICENCIATURA EM QUÍMICA (Câmpus Capivari)'
            ],
            [
                'codigo' => 'CPV18075',
                'descricao' => 'TÉCNICO EM MANUTENÇÃO E SUPORTE EM INFORMÁTICA (Câmpus Capivari)'
            ],
            [
                'codigo' => 'CPV18201',
                'descricao' => 'TECNOLOGIA EM ANÁLISE E DESENVOLVIMENTO DE SISTEMAS (Câmpus Capivari)'
            ]
        ]);
    }
}
