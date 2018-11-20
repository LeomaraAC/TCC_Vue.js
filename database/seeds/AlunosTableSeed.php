<?php

use Illuminate\Database\Seeder;

class AlunosTableSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\Aluno::insert([
            [
                'cpf' => '35612684800',
                'rg' => '34.077.158-6',
                'nome' => 'Hugo Henry Anderson Freitas',
                'data_nascimento' => '10/06/2000',
                'nome_mae' => 'Bruna Aline Sara',
                'nome_pai' => 'Felipe Giovanni Freitas',
                'sexo' => 'M',
                'email_pessoal' => 'hugohenryandersonfreitas-88@citadini.imb.br',
                'estado_civil' => 'Solteiro',
                'etnia' => 'Parda',
                'endereco' => 'Rua Barão do Rio Branco,532,Centro,13360-970,Capivari-SP'
            ],
            [
                'cpf' => '32553750889',
                'rg' => '46.830.408-3',
                'nome' => 'Fernando Bryan Nelson Vieira',
                'data_nascimento' => '27/02/1992',
                'nome_mae' => 'Tatiane Juliana',
                'nome_pai' => 'Bernardo Guilherme Vitor Vieira',
                'sexo' => 'M',
                'email_pessoal' => 'fernandobryannelsonvieira@jovempanfmtaubate.com.br',
                'estado_civil' => 'Casado',
                'etnia' => 'Branco',
                'endereco' => 'Rua Mauricio Allain,205,Centro,13370-970,Rafard-SP'
            ],
            [
                'cpf' => '24306134849',
                'rg' => '26.601.069-6',
                'nome' => 'Fabiana Nina Jéssica da Paz',
                'data_nascimento' => '24/01/1999',
                'nome_mae' => 'Isabel Letícia',
                'nome_pai' => 'Oliver Carlos Tomás da Paz',
                'sexo' => 'F',
                'email_pessoal' => 'fabiananinajessicadapaz@victorseguros.com.br',
                'estado_civil' => 'Solteiro',
                'etnia' => 'Negra',
                'endereco' => 'Rua Prudente de Moraes,459,Centro,13390-970,Rio das Pedras-SP'
            ],
            [
                'cpf' => '13333669811',
                'rg' => '21.422.683-9',
                'nome' => 'Allana Márcia Araujo Rocha',
                'data_nascimento' => '17/04/1988',
                'nome_mae' => 'Renata Sophie Araujo',
                'nome_pai' => 'Nicolas Raul Rocha',
                'sexo' => 'F',
                'email_pessoal' => 'allanamarciarocha@nokia.com',
                'estado_civil' => 'Viuva',
                'etnia' => 'Branca',
                'endereco' => 'Rua Ribeiro Couto,257,Parque Santa Cecília,13420-087,Piracicaba-SP'
            ]
        ]);

    }
}
