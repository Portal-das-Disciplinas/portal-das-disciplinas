<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function index(){
        return view('profile');
    }

    public function update(Request $request)
    {

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|max:12',
            'password_confirmation' => 'nullable|required_with:new_password|same:new_password'
        ];

        $request->validate($rules);
        $user = Auth::user();

        if (!is_null($request->input('current_password'))) {
            if (Hash::check($request->input('current_password'), $user->password)) {
                $user->password = bcrypt($request->input('new_password'));
                $user->updated_at = now();
                $user->email = $request->input('email');
                $user->save();
            } else {
                return redirect()->back()->withInput()
                    ->withErrors(['current_password' => 'Senha atual incorreta']);
            }
        }

        return back()
            ->with('success', 'Dados atualizado com sucesso!');

    }

}
