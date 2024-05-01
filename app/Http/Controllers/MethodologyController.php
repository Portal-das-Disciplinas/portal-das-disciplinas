<?php

namespace App\Http\Controllers;

use App\Models\Methodology;
use App\Services\MethodologyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MethodologyController extends Controller
{
    public function update(Request $request)
    {
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            $methodology =  $methodologyService->update($request->idMethodology, $request->name, $request->description);
            return response()->json($methodology);
        }
    }
}
