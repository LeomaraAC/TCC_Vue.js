<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;
    protected $table = 'cursos';
    protected $primaryKey = 'idCurso';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    protected $fillable = ['descricao', 'sigla'];

    public function aluno () {
        return $this->hasMany(Aluno::class, 'idCurso');
    }
}
