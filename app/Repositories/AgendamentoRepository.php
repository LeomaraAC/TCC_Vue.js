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
}