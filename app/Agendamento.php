<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use SoftDeletes;
    protected $table = 'agendamento';
    protected $primaryKey = 'idAgendamento';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    protected $fillable = [
        'dataPrevisto',
        'horaPrevistaInicio',
        'horaPrevistaFim',
        'formaAtendimento',
        'responsavel',
        'status',
        'idTipo_atendimento',
        'idUser',
        'dataRemarcada'
    ];

    public function matricula()
    {
        return $this->belongsToMany(Matricula::class,'agendamento_matricula','idAgendamento','prontuario');
    }
}
