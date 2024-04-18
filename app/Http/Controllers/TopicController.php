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
                'discipline_id' => 'required'
            ]);

            $discipline = Discipline::find($request->discipline_id);

            $topic = new Topic(['title' => $request->title]);

            if (!is_null($request->parent_topic_id)) {
                $topic->parent_topic_id = $request->parent_topic_id;
            }

            $topic->save();

            $discipline->topics()->attach($topic->id);

            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function update(Request $request, $topic_id) {
        DB::beginTransaction();

        try {
            $request->validate([
                "required_level" => "required",
                "title" => "string"
            ]);

            $topic = Topic::find($topic_id);

            $topic->title = $request->title;
            
            if ($topic->required_level > 0 && $topic->required_level <=5) {
                $topic->required_level = $request->required_level;
            } else {
                $topic->required_level = null;
            }

            $topic->save();

            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->back()->withInput();
        }
    }

    public function destroy($discipline_id, $topic_id) {
        DB::beginTransaction();

        try {
            $topic = Topic::find($topic_id);

            $topic->disciplines()->detach($discipline_id);
            $topic->delete();

            DB::commit();

            return true;
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function getSubtopicsList($discipline_id, $topic_id) {
        $discipline = Discipline::find($discipline_id);
        $topic = Topic::find($topic_id);
        $subtopics = $topic->subtopics;

        return view('components.subtopics', compact('subtopics', 'topic'))->render();
    }
}
