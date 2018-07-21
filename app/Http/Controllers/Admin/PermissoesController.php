<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Permissao;

class PermissoesController extends Controller
{
    public function index($campo = 'nomeTela',$order = 'asc', $filter = null) {
        return Permissao::orderBy($campo, $order)->where('nomeTela', 'like', '%'.$filter.'%')->paginate(5);
    }
}
