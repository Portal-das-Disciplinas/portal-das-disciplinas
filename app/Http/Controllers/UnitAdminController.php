<?php

namespace App\Http\Controllers;

use App\Exceptions\ExistingDataException;
use App\Exceptions\NotAuthorizedException;
use App\Services\InstitutionalUnitService;
use App\Services\UnitAdminService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UnitAdminController extends Controller
{
    protected $theme;
    protected $unitAdminService;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->unitAdminService = new UnitAdminService();
        $this->middleware('admin');
    }

    public function index(){

        $unitAdmins = $this->unitAdminService->list();
        $institutionalUnitService = new InstitutionalUnitService();
        $institutionalUnits = $institutionalUnitService->listAll(); 
        return view('unit_admin/index',[
            'unitAdmins' => $unitAdmins,
            'institutionalUnits' => $institutionalUnits

        ])->with(['theme' => $this->theme]);
    }

    public function store(Request $request){
        
        try{
            $this->unitAdminService->save($request->name, $request->email,$request->password, $request->{'unit-id'});
            return redirect()->back()->with(['success_message' => 'Administrador de unidade cadastrado com sucesso!']);

        }catch(ExistingDataException $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['EXISTING_DATA_EXCEPTION' => $e->getMessage()]);
            
        }catch(Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['store_error' => 'Não foi possível cadastrar.']);
        }
    }

    public function destroy($id){
        try{
            $this->unitAdminService->delete($id);
            return redirect()->back()->with(['success_message' => 'Administrador de unidade removido.']);

        }catch(NotAuthorizedException $e){
            return redirect()->back()->withErrors(['auth_error' => $e->getMessage()]);

        }catch(Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['delete_error' => 'Não foi possível remover']);
        }
    }
}
