<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classification;
use App\Models\Discipline;

class ClassificationController extends Controller
{
    public function index()
    {
        $classifications = Classification::all();
        return view('admin.classification.index')
            ->with('classifications', $classifications);
    }

    public function create()
    {
        return view('admin.classification.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:classifications',
            'type_a' => 'required',
            'type_b' => 'required',
        ]);

        $classification = new Classification();
        $classification->name = $request->name;
        $classification->type_a = $request->type_a ?? '';
        $classification->type_b = $request->type_b ?? '';
        $classification->description = $request->description ?? '';
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
        return view('admin.classification.form')->with('classification', $classification);
    }

    public function edit($id)
    {
        $classification = Classification::find($id);
        return view('admin.classification.form')
            ->with('classification', $classification)
            ->with('id', $id)
            ->with('edit', true);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'type_a' => 'required',
            'type_b' => 'required',
        ]);

        $classification = Classification::find($id);
        $classification->name = $request->name;
        $classification->type_a = $request->type_a ?? '';
        $classification->type_b = $request->type_b ?? '';
        $classification->description = $request->description ?? '';
        $classification->save();

        return redirect()->route('classificacoes.index');
    }

    public function destroy($id)
    {
        $classification = Classification::find($id);
        $classification->delete();

        return redirect()->route('classificacoes.index');
    }
}
