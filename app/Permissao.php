<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    protected $table = 'permissoes';
    protected $primaryKey = 'idTelas';


    public function grupos()
    {
        return $this->belongsToMany(Grupo_Usuarios::class,'permissoes_grupo','idTelas','idGrupo');
    }
}
