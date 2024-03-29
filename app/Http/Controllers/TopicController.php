<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Topic;

class TopicController extends Controller
{
    public function store(Request $request) {
        DB::beginTransaction();

        $request->validate([
            'title' => 'required|string',
        ]);

        try {
            $disciplines = Discipline::all();
            $discipline = $disciplines->find($request->discipline_id);

            $topic = new Topic(['title' => $request->title]);
            $topic->save();

            $discipline->topics()->attach($topic->id);

            DB::commit();

            return response()->json([
                'ok' => 'true',
                'topic' => $topic
            ]);
        } catch (\Exception $exception) {
            DB::rollback();
            // return redirect()->back()->withInput();
            return response()->json([
                'ok' => 'false',
                'error' => $exception
            ]);
        }
    }
}
