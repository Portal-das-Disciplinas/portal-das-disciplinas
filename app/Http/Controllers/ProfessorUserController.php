<?php

namespace App\Http\Controllers;

use App\Http\Requests\Professor\CreateRequest;
use App\Http\Requests\Professor\StoreRequest;
use App\Models\Professor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
class ProfessorUserController extends Controller
{
    const VIEW_PATH = "admin.";

    public function index()
    {
        $professors = Professor::query()->with([
            'user',
        ])->get();
        return view(self::VIEW_PATH.'index', compact('professors'));
    }

    public function create(CreateRequest $request)
    {
        return view(self::VIEW_PATH.'create');
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try
        {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'role_id' => '3'
            ]);

            Professor::create([
                'name' => $user->name,
                'profile_pic_link' => null,
                'public_email' => $request->get('public_email', $user->email),
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('professores.index');

        }catch(\Exception $exception)
        {
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id){
        $professor = Professor::findOrFail($id);
        $professor->user->delete();
        return redirect()->route('professores.index');
    }
}

