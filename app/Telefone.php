<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telefone extends Model
{
    use SoftDeletes;
    protected $table = 'telefone';
    protected $primaryKey = 'idTelefone';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    protected $fillable = [
        'numero',
        'cpf'
    ];

    public function aluno () {
        return $this->belongsTo(Aluno::class, 'cpf');
    }
}
