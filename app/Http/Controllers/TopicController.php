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
        
        try {
            $request->validate([
                'title' => 'required|string',
            ]);

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

    public function update(Request $request, $topic_id) {
        DB::beginTransaction();

        try {
            $request->validate([
                "required_level" => "required"
            ]);

            $topic = Topic::find($topic_id);
            $topic->required_level = $request->required_level;

            if (!is_null($request->parent_topic)) {
                $topic->parent_topic_id = $request->parent_topic;
            }

            $topic->save();

            DB::commit();

            return response()->json([
                'ok' => 'true',
            ]);
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'ok' => 'false',
                'error' => $exception
            ]);
        }
    }
}
