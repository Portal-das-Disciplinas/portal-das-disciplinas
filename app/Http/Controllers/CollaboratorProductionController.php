<?php

namespace App\Http\Controllers;

use App\Models\CollaboratorProduction;
use Illuminate\Http\Request;

class CollaboratorProductionController extends Controller
{
    public function storeListJson(Request $request){

        return $request->jsonData;
    }
}
