<?php

namespace App\Http\Controllers;

use App\Services\EducationLevelService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EducationLevelController extends Controller
{
    protected $theme;
    protected $educationLevelService;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
        $this->middleware('admin')->except(['index']);
        $this->educationLevelService = new EducationLevelService();
    }

    public function index(Request $request)
    {
        $educationLevels = $this->educationLevelService->list();

        return view('education_level/index', [
            'educationLevels' => $educationLevels
        ])->with('theme', $this->theme);
    }

    public function store(Request $request)
    {
        $this->educationLevelService->save($request->value, $request->{'priority-level'});
        return redirect()->back()->with([
            'success_message' => 'Cadastrado com sucesso'
        ]);
    }

    public function destroy($id){
        try{
            $this->educationLevelService->delete($id);
            return redirect()->back()->with(['success_message' => 'Nível apagado com sucesso!']);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return redirect()->back()->withErrors(['delete_error' => 'Não foi possível deletar.']);
        }
    }
}
