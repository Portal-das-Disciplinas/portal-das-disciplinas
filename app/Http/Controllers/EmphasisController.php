<?php

namespace App\Http\Controllers;

use App\Models\Emphasis;
use Illuminate\Http\Request;

/**
 * @class EmphasisController
 * @brief Controlador para gerenciar operações relacionadas às ênfases.
 */
class EmphasisController extends Controller
{
    /**
     * @brief Exibe a página inicial para as ênfases.
     * @return void
     */
    public function index() 
    {
        $emphasis = Emphasis::all();
    }
}
