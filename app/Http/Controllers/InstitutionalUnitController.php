<?php

namespace App\Http\Controllers;

use App\Exceptions\ExistingDataException;
use App\Exceptions\IntegrityConstraintViolationException;
use App\Exceptions\InvalidInputException;
use App\Services\InstitutionalUnitService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class InstitutionalUnitController extends Controller
{
    protected $theme;
    protected $institutionalUnitService;

    public function __construct()
    {

        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->institutionalUnitService = new InstitutionalUnitService();
        $this->middleware('admin');
    }

    public function index(Request $request)
    {

        $units = $this->institutionalUnitService->listAll();

        return view('institutional_unit/index', [
            'units' => $units
        ])->with('theme', $this->theme);
    }

    public function store(Request $request)
    {

        try {
            $this->institutionalUnitService->save($request->{'unit-acronym'}, $request->{'unit-name'});
            return redirect()->back()->with(['success_message' => 'Unidade cadastrada com Sucesso.']);
        } catch (InvalidInputException $e1) {
            return redirect()->back()->withErrors(['store_error' => $e1->getMessage()]);
        } catch (ExistingDataException $e2) {
            return redirect()->back()->withErrors(['store_error' => $e2->getMessage()]);
        } catch (Exception $e3) {
            Log::error($e3->getMessage());
            return redirect()->back()->withErrors(['store_error' => 'Não foi possível cadastrar.']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $this->institutionalUnitService->update($id, $request->{'unit-acronym'}, $request->{'unit-name'});
            return redirect()->back()->with(['success_message' => 'Unidade atualizada com Sucesso.']);
        } catch (InvalidInputException $e1) {
            return redirect()->back()->withErrors(['input_error' => $e1->getMessage()]);
        } catch (ExistingDataException $e2) {
            return redirect()->back()->withErrors(['input_error' => $e2->getMessage()]);
        } catch (Exception $e3) {
            Log::error($e3->getMessage());
            return redirect()->back()->withErrors(['store_error' => 'Não foi possível atualizar.']);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $this->institutionalUnitService->delete($request->id);
            return redirect()->back()->with(['success_message' => 'Unidade deletada com sucesso.']);
        } catch (IntegrityConstraintViolationException $e) {
            return redirect()->back()->withErrors(['query_exception_error' => $e->getMessage()]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['delete_error' => 'Não foi possível deletar a unidade']);
        }
    }
}
