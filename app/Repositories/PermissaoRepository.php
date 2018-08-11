<?php

namespace App\ Repositories;

use App\Repositories\Contracts\Base\BaseRepository;
use App\Permissao;


class PermissaoRepository  extends  BaseRepository
{
    protected $model;

    public function __construct(Permissao $permisso) {
        $this->model = $permisso;
    }
}