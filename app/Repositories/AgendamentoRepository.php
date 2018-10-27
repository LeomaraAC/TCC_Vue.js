<?php
namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Agendamento;

class AgendamentoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Agendamento $agendamento) {
        $this->model = $agendamento;
    }
    private function validaHorarioVisivel($horaInicial, $horaFinal, $data) {
       
        $atendimentos = $this->model
                             ->where('todos', '=', true)
                             ->where('dataPrevisto', '=', $data)
                             ->where(function($query) use ($horaFinal, $horaInicial) {
                                $query->where(function($q) use ($horaFinal, $horaInicial) {
                                   $q->where('horaPrevistaInicio', '>=', $horaInicial)
                                     ->where('horaPrevistaFim', '<=', $horaFinal); 
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '>=', $horaInicial)
                                      ->where('horaPrevistaInicio', '<', $horaFinal)
                                      ->where('horaPrevistaFim', '>=', $horaFinal);
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '<=', $horaInicial)
                                      ->where('horaPrevistaFim', '>', $horaInicial)
                                      ->where('horaPrevistaFim', '<=', $horaFinal);
                                })
                                ->orwhere(function($q) use ($horaFinal, $horaInicial){
                                    $q->where('horaPrevistaInicio', '<=', $horaInicial)
                                      ->where('horaPrevistaFim', '>=', $horaFinal);
                                });
                             })
                             ->count();
        if($atendimentos == 0)
            return true;
        else
            return false;
    }
}