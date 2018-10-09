<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoAtendimento extends Model
{
    use SoftDeletes;
    protected $table = 'tipo_atendimento';
    protected $primaryKey = 'idTipo_atendimento';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    protected $fillable = [
        'descricao'
    ];

    // public function atendimento () {
    //     return $this->hasMany(Atendimento::class, 'idTipo_atendimento');
    // }
}
