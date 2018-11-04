<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matricula extends Model
{
    use SoftDeletes;
    protected $table = 'matricula';
    protected $primaryKey = 'prontuario';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'prontuario',
        'codigo_curso',
        'previsao_conclusao',
        'ano_ingresso',
        'data_integralizacao',
        'forma_ingresso',
        'instituicao_anterior',
        'situacao_curso',
        'situacao_periodo',
        'turma',
        'email_academico',
        'observacao_historico',
        'Observacoes',
        'idAluno',
        
    ];

    public function aluno () {
        return $this->belongsTo(Aluno::class, 'idAluno');
    }
    public function curso() {
        return $this->belongsTo(Curso::class, 'codigo_curso');
    }

    public function agendamento()
    {
        return $this->belongsToMany(Agendamento::class,'agendamento_matricula','prontuario','idAgendamento');
    }
}
