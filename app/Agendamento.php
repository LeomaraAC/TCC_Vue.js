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
        'horarioPrevisto',
        'formaAtendimento',
        'todos',
        'status',
        'idTipo_atendimento',
        'idUser'
    ];

    public function matricula()
    {
        return $this->belongsToMany(Matricula::class,'agendamento_matricula','idAgendamento','prontuario');
    }
}
