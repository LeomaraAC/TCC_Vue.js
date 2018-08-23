<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aluno extends Model
{
    use SoftDeletes;
    protected $table = 'alunos';
    protected $primaryKey = 'idAluno';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    protected $fillable = ['prontuario', 'nome', 'email', 'telefone', 'semestreAno', 'Observacoes',
    'statusMatricula', 'motivoCancelamento', 'idCurso'];

    public function curso () {
        return $this->belongsTo(Curso::class, 'idCurso');
    }
}
