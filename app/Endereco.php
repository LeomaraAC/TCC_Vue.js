<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Endereco extends Model
{
    use SoftDeletes;
    protected $table = 'endereco';
    protected $primaryKey = 'idEndereco';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    protected $fillable = ['rua','numero','bairro','cep','cidade','estado'];

    public function aluno () {
        return $this->hasMany(Aluno::class, 'idEndereco');
    }
}
