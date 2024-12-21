<?php

namespace App\Http\Controllers;

use App\Services\InstitutionalUnitService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstitutionalUnitController extends Controller
{
    protected $theme;

    public function __construct()
    {

        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin');
    }

    public function index(Request $request){

        $service = new InstitutionalUnitService();
        $units = $service->listAll();

        return view('institutional_unit/index',[
            'units' => $units
        ])->with('theme',$this->theme);
    }

    public function destroy(Request $request){
        $service = new InstitutionalUnitService();
        try{
            $service->delete($request->id);
            return redirect()->back()->with(['success_message'=>'Unidade deletada com sucesso.']);
        }catch(Exception $e){
            return redirect()->back()->withErrors(['delete_error' => $e->getMessage()]);
            Log::error($e->getMessage());
        }
        
    }
}
