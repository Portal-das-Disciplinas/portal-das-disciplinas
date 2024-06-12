<?php

namespace App\Http\Controllers;

use App\Exceptions\ExistingDataException;
use App\Exceptions\NotAuthorizedException;
use App\Models\Methodology;
use App\Services\MethodologyService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use LengthException;
use Illuminate\Support\Facades\Storage;

class MethodologyController extends Controller
{
    const VIEW_PATH = 'admin.methodologies.';

    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    public function index() 
    {
        $methodologyService = new MethodologyService();
        $methodologies = $methodologyService->listAllMethodologies();

        return view(self::VIEW_PATH.'index', compact('methodologies'))->with('theme', $this->theme);
    }

    public function list(Request $request)
    {
        $methodologyService = new MethodologyService();

        if ($request->ajax()) {
            $methodologies = $methodologyService->listAllMethodologies();
            return response()->json($methodologies);
        }
    }

    public function store(Request $request)
    {
        $methodologyService = new MethodologyService();

        if ($request->ajax()) {
            try{
                
                if (!isset($request->methodology['professor_id'])) {
                    $methodology = $methodologyService
                    ->saveMethodology($request->methodology['name'], $request->methodology['description']);
                } else {
                    $methodology = $methodologyService
                    ->saveMethodology($request->methodology['name'], $request->methodology['description'], $request->methodology['professor_id']);
                }

                return response()->json($methodology);
            }catch(LengthException $e){
                return response()->json(['error' => $e->getMessage()],400);

            } catch(ExistingDataException $e){
                return response()->json(['error' => 'Já existe uma metodologia cadastrada com o mesmo nome.'],409);

            } catch(Exception $e){
                Log::error($e);
                return response()->json(['error' => 'Erro desconhecido'],500);

            }
            
            
        }
    }

    public function update(Request $request)
    {
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            $methodology =  $methodologyService->update($request->idMethodology, $request->name, $request->description);
            return response()->json($methodology);
        }
    }

    public function destroy(Request $request)
    {
        $methodologyService = new MethodologyService();
        if ($request->ajax()) {
            try {
                $methodology = $methodologyService->deleteMethodology($request->id_methodology);
                return response()->json($methodology);
            } catch (NotAuthorizedException $e) {
                return response()->json(['error' => $e->getMessage()], 403);
            } catch (Exception $e) {
                return response()->json(['error' => 'Um erro aconteceu'], 500);
            }
        }
    }
}
