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
        return view(self::VIEW_PATH . 'professor.' . 'index', compact('professors'));
    }

    public function create(CreateRequest $request)
    {
        return view(self::VIEW_PATH . 'professor.' . 'create');
    }

    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        try {
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
                'rede_social1' => $request->get('rede_social1', $user->rede_social1),
                'link_rsocial1' => $request->get('link_rsocial1', $user->link_rsocial1),
                'rede_social2' => $request->get('rede_social2', $user->rede_social2),
                'link_rsocial2' => $request->get('link_rsocial2', $user->link_rsocial2),
                'rede_social3' => $request->get('rede_social3', $user->rede_social3),
                'link_rsocial3' => $request->get('link_rsocial3', $user->link_rsocial3),
                'rede_social4' => $request->get('rede_social4', $user->rede_social4),
                'link_rsocial4' => $request->get('link_rsocial4', $user->link_rsocial4),
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('professores.index');
        } catch (\Exception $exception) {
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        $professor = Professor::findOrFail($id);
        $professor->user->delete();
        return redirect()->route('professores.index');
    }
}
