<?php

namespace App\Http\Controllers;

use App\Services\APISigaa\APISigaaService;
use Illuminate\Http\Request;

class ApiSistemasController extends Controller
{
    function getTurmasPorComponente(Request $request){

        $service = new APISigaaService();
        $turmas = $service->getTurmasPorComponente($request['codigo-componente'], $request['ano'], $request['periodo']);
        $data = array([
            "response" => $request['ano']
        ]);
        return response()->json($turmas);
    }
}
