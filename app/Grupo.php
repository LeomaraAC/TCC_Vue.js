<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grupo extends Model
{
    use SoftDeletes;
    protected $table = 'grupo';
    protected $primaryKey = 'idGrupo';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at', 'created_at', 'updated_at'];
    protected $fillable = ['nomeGrupo'];
    public $timestamps = false;

    public function funcoes()
    {
        return $this->belongsToMany(Permissao::class,'permissoes_grupo','idGrupo','idTelas');
    }
    public function usuarios () {
        return $this->hasMany(User::class, 'idGrupo');
    }
}
