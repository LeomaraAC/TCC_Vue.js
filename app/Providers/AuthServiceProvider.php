<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Permissao;
use App\User;
use App\Grupo;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // $permissoes = Permissao::all();
        // foreach($permissoes as $permissao) {
        //     Gate::define($permissao->nome, function (User $user) use($permissao) {
        //         return $user->hasPermission($permissao);
        //     });  
        // }

        // $modulos = Permissao::all()->groupBy('modulo')->keys()->all();
        // foreach($modulos as $modulo) {
        //     Gate::define($modulo, function (User $user) use($modulo) {
        //         return $user->hasModulo($modulo);
        //     }); 
        // }

        // Gate::define('administracao', function (User $user){
        //     $grupo = $user->hasModulo('grupo');
        //     $user = $user->hasModulo('usuario');
        //     return $grupo || $user;
        // });
        
        // Gate::define('atendimento', function (User $user){
        //     $tipo = $user->hasModulo('tipo_atendimento');
        //     $agendamento = $user->hasModulo('agendamento');
        //     return $tipo || $agendamento;
        // });
    }
}
