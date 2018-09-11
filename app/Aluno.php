<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aluno extends Model
{
    use SoftDeletes;
    protected $table = 'alunos';
    protected $primaryKey = 'cpf';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    protected $fillable = [
        'cpf', 
        'rg', 
        'nome', 
        'data_nascimento', 
        'nome_mae', 
        'nome_pai', 
        'sexo', 
        'responsavel',
        'email_pessoal', 
        'email_responsavel', 
        'estado_civil', 
        'naturalidade',
        'deficiencia',
        'etnia',
        'necessidades_especiais',
        'renda_bruta',
        'renda_per_capta',
        'superdotacao',
        'escola_origem',
        'tipo_escola_origem',
        'transtorno',
        'idEndereco'
    ];

    public function endereco () {
        return $this->belongsTo(Endereco::class, 'idEndereco');
    }
    public function matricula()
    {
        return $this->hasMany(Matricula::class, 'cpf');
    }
    public function telefone() {
        return $this->hasMany(Telefone::class, 'cpf');
    }
}
