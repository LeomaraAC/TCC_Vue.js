<?php
namespace App\ Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Endereco;

class EnderecoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Endereco $end) {
        $this->model = $end;
    }
}