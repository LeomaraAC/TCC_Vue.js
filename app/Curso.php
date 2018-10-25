<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Curso extends Model
{
    use SoftDeletes;
    protected $table = 'cursos';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $dates = ['deleted_at'];
    protected $hidden = ['deleted_at'];
    protected $fillable = ['codigo','descricao'];
    public $timestamps = false;

    public function matricula () {
        return $this->hasMany(Matricula::class, 'codigo_curso');
    }
}
