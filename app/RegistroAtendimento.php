<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegistroAtendimento extends Model
{
    use SoftDeletes;
    protected $table = 'registro_atendimento';
    protected $primaryKey = 'idRegistro';
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    public $timestamps = false;
    protected $fillable = [
        'dataRealizado',
        'horaRealizado',
        'comparecimentoFamiliar',
        'grauParentesco',
        'resumo',
        'idAgendamento',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class,'registro_user','idRegistro','idUser');
    }

    public function agendamento() {
        return $this->belongsTo(Agendamento::class, 'idAgendamento');
    }
}
