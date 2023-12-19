<?php

namespace App\Http\Controllers;

use App\Services\APISigaa\APISigaaService;
use Illuminate\Http\Request;

/**
 * @class ApiSistemasController
 * @brief Classe de controle para lidar com solicitações de API relacionadas a sistemas.
 */
class ApiSistemasController extends Controller
{
    /**
     * @brief Recupera turmas com base no código do componente, ano e período fornecidos.
     * @param Request $request O objeto de requisição HTTP.
     * @return \Illuminate\Http\JsonResponse Resposta JSON contendo as turmas recuperadas.
     */
    function getTurmasPorComponente(Request $request){

        $service = new APISigaaService();
        $turmas = $service->getTurmasPorComponente($request['codigo-componente'], $request['ano'], $request['periodo']);
        $data = array([
            "response" => $request['ano']
        ]);
        return response()->json($turmas);
    }
}
