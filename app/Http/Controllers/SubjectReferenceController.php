<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidInputException;
use App\Exceptions\NotImplementedException;
use App\Models\Discipline;
use App\Models\SubjectReference;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubjectReferenceController extends Controller
{
    function store(Request $request)
    {
        $discipline = Discipline::find($request->{'discipline_id'});
        if ((Auth::user() && Auth::user()->isAdmin) ||
            (Auth::user() && Auth::user()->isProfessor && Auth::user()->professor->id == $discipline->professor->id)
        ) {
            if ($request->ajax()) {
                try {
                    DB::beginTransaction();
                    if (!isset($request->{'reference'}) || (strlen($request->{'reference'}) == 0)) {
                        throw new InvalidInputException("Nome da referência muito curto.");
                    }
                    $subjectReference = SubjectReference::create([
                        'value' => $request->{'reference'},
                        'discipline_id' => $request->{'discipline_id'}
                    ]);
                    DB::commit();
                    return response()->json($subjectReference, 201);
                } catch (InvalidInputException $e) {
                    DB::rollBack();
                    return response()->json(['error' => $e->getMessage()], 400);
                } catch (Exception $e) {
                    DB::rollBack();
                    Log::error($e->getMessage());
                    return response()->json(['error' => "Um erro aconteceu"], 500);
                }
            } else {
                throw new NotImplementedException();
            }
        }else{
            if($request->ajax()){
                return response()->json(['error' => 'Operação não permitida'],403);
            }else{
                throw new NotImplementedException();
            }
        }
    }

    function destroy(Request $request, $id)
    {
        $subjectReference = SubjectReference::find($id);
        $discipline = Discipline::find($subjectReference->{'discipline_id'});
        if ((Auth::user() && Auth::user()->isAdmin) ||
            (Auth::user() && Auth::user()->isProfessor && Auth::user()->professor->id == $discipline->professor->id)
        ) {
            if ($request->ajax()) {
                $subjectReference->delete();
                return response()->json(['message' => 'OK'], 200);
            } else {
                throw new NotImplementedException();
            }
        } else {
            if($request->ajax()){
                return response()->json(['error' => 'Operação não permitida'], 403);
            }else{
                throw new NotImplementedException();
            }
            
        }
    }
}
