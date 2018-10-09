<?php
namespace App\Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Telefone;

class TelefoneRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Telefone $telefone) {
        $this->model = $telefone;
    }
}