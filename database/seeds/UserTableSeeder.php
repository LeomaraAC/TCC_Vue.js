<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(App\User::class)->create();
        $data = array(
            array(
                'nome' => 'Master',
                'prontuario' => 'cv1002000',
                'email' => 'master@ifsp.com',
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => str_random(10),
                'idGrupo' => 1
            ),
            array(
                'nome' => 'User Apagar',
                'prontuario' => 'cv1102000',
                'email' => 'apagar@ifsp.com',
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => str_random(10),
                'idGrupo' => 1
            ),
            array(
                'nome' => 'User Editar',
                'prontuario' => 'cv1112000',
                'email' => 'editar@ifsp.com',
                'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'remember_token' => str_random(10),
                'idGrupo' => 1
            )
        );
        DB::table('users')->insert($data);
    }
}
