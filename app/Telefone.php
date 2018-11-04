<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefone extends Model
{
    protected $table = 'telefone';
    protected $primaryKey = 'idTelefone';
    public $timestamps = false;
    protected $fillable = [
        'numero',
        'idAluno'
    ];

    public function aluno () {
        return $this->belongsTo(Aluno::class, 'idAluno');
    }
}
