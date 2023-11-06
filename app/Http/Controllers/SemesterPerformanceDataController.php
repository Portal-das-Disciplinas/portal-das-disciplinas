<?php

namespace App\Http\Controllers;

use App\Models\SemesterPerformanceData;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SemesterPerformanceDataController extends Controller
{
    protected $theme;
    function __construct()
    {
        $this->middleware('admin');
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    public function index()
    {

        $semesterPerformanceData = SemesterPerformanceData::query()->orderBy('year')->orderBy('period')->get();
        return view('discipline_performance_data/semester_performance_data')
            ->with('theme', $this->theme)
            ->with('semesterPerformanceData', $semesterPerformanceData);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $data = SemesterPerformanceData::find($id);
            $data->delete();
            DB::commit();
            return redirect()->back()->with("status","Dado removido com sucesso!");
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return redirect()->back()->withErrors(['delete'=>'Erro ao remover o dado']);
        }
    }
}
