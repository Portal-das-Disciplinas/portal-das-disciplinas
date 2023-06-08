<?php

namespace App\Http\Controllers;

use App\Models\Professor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function PHPSTORM_META\map;
/**
 * Classe que trata as tarefas relacionadas com o model User.
 */
class UsersController extends Controller
{

    public function index()
    {
        $is_teacher = Auth::user()->professor ?? false;
        return view('profile')
            ->with('is_teacher', $is_teacher);
    }

    /**
     * Atualiza um usuário
     * @param $request Objeto contendo as informações da requisição http.
     */
    public function update(Request $request)
    {

        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::user()->id,
            'current_password' => 'required',
            'new_password' => 'nullable|min:8|max:12',
            'password_confirmation' => 'nullable|required_with:new_password|same:new_password',
            'public_link' => 'nullable'
        ];

        $request->validate($rules);
        $user = Auth::user();
        $professor = Auth::user()->professor;

        if (Hash::check($request->input('current_password'), $user->password)) {
            if (!empty($request->input('new_password'))) {
                $user->password = bcrypt($request->input('new_password'));
            }
            $user->updated_at = now();
            $user->email = $request->input('email');
            $user->save();

            if (isset($professor)) {
                $professor->public_email = $request->input('public_email');
                $professor->public_link = $request->input('public_link');
                $professor->save();
            }
        } else {
            return redirect()->back()->withInput()
                ->withErrors(['current_password' => 'Senha atual incorreta']);
        }

        return back()
            ->with('success', 'Dados atualizado com sucesso!');
    }
}
