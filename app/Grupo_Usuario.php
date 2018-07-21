<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo_Usuario extends Model
{
    use SoftDeletes;
    protected $table = 'grupo_usuarios';
    protected $primaryKey = 'idGrupo';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];

    public function funcoes()
    {
        return $this->belongsToMany(Permissao::class,'permissoes_grupo','idGrupo','idTelas');
    }
}
