<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classification;
use App\Models\Discipline;
use Illuminate\Support\Facades\Storage;


class ClassificationController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    public function index()
    {
        $classifications = Classification::all()->sortBy('order');
        return view('admin.classification.index')
            ->with('classifications', $classifications)
            ->with('theme', $this->theme);
    }

    public function create()
    {
        return view('admin.classification.form')
            ->with('theme', $this->theme);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:classifications',
            'type_a' => 'required',
            'type_b' => 'required',
            'system_name' => 'required'
        ]);

        $lastOrder = Classification::max('order');

        $classification = new Classification();
        $classification->name = $request->name;
        $classification->type_a = $request->type_a ?? '';
        $classification->type_b = $request->type_b ?? '';
        $classification->description = $request->description ?? '';
        $classification->order = $lastOrder + 1;
        $classification->save();

        $disciplines = Discipline::all();
        $disciplines->each(function ($discipline) use ($classification) {
            $discipline->classificationsDisciplines()->create([
                'classification_id' => $classification->id,
                'discipline_id' => $discipline->id,
                'value' => 50,
            ]);
        });

        return redirect()->route('classificacoes.index');
    }
    
    public function show($id)
    {
        $classification = Classification::find($id);
        return view('admin.classification.form')
            ->with('classification', $classification)
            ->with('theme', $this->theme);
    }

    public function edit($id)
    {
        $classification = Classification::find($id);
        return view('admin.classification.form')
            ->with('classification', $classification)
            ->with('id', $id)
            ->with('edit', true)
            ->with('theme', $this->theme);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'type_a' => 'required',
            'type_b' => 'required',
            'system_name' => 'required'
        ]);

        $classification = Classification::find($id);
        $classification->name = $request->name;
        $classification->type_a = $request->type_a ?? '';
        $classification->type_b = $request->type_b ?? '';
        $classification->description = $request->description ?? '';
        $classification->save();

        return redirect()->route('classificacoes.index');
    }

    function updateClassificationOrder(Request $request){
       $data =  json_decode($request->idList);
       foreach($data as $index => $idClassification){
            $classification = Classification::find($idClassification);
            $classification->order = $index;
            $classification->save();
       }

        return response()->json($data);
    }

    public function destroy($id)
    {
        $classification = Classification::find($id);
        $classification->delete();

        $classifications = Classification::All()->sortBy('order');
        foreach($classifications as $index => $classification){
            $classification->order = $index;
            $classification->save();
        }

        return redirect()->route('classificacoes.index');
    }
}
