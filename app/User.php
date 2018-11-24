<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Permissao;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;
    
    protected $primaryKey = 'idUser';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome', 'prontuario', 'email', 'password','idGrupo'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at', 'deleted_at'
    ];

    public function grupo () {
        return $this->belongsTo(Grupo::class, 'idGrupo');
    }
    
    public function registroAtendimento(){
        return $this->belongsToMany(RegistroAtendimento::class,'registro_user','idUser','idRegistro');
    }

    public function hasPermission(Permissao $permissao) {
       return $this->hasGrupo($permissao->grupos);
    }
    public function hasModulo($modulo) {
        return $this->grupo->funcoes->contains('modulo', $modulo);
    }

    public function hasGrupo($grupos) {
       if(is_array($grupos) || is_object($grupos)) {
           return !! $grupos->intersect([$this->grupo])->count();
       }
       return $this->grupo->contains('nome', $grupos);
    }
}
