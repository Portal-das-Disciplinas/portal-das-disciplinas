<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Professor;


use function PHPSTORM_META\map;
/**
 * Classe que trata as tarefas relacionadas com o model User.
 */
class UsersController extends Controller
{

    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    public function index()
    {
        $is_teacher = Auth::user()->professor ?? false;
        $opinionLinkForm = Link::where('name','opinionForm')->first();
        return view('profile')
            ->with('is_teacher', $is_teacher)
            ->with('theme', $this->theme)
            ->with('opinionLinkForm',$opinionLinkForm)
            ->with('showOpinionForm',true);
    }

    /**
     * Atualiza um usuário.
     * Função chamada quando o usuário não é admin.
     * @param $request Objeto contendo as informações da requisição http.
     */
    public function update(Request $request)
    {

        $user = Auth::user();
        $professor = Auth::user()->professor;

        if (Hash::check($request->input('current_password'), $user->password)) {
            if (!empty($request->input('new_password'))) {
                if ($request->input('new_password') == $request->input('password_confirmation')) {
                    $user->password = bcrypt($request->input('new_password'));
                } else {
                    return redirect()->back()->withInput()->withErrors(['password_confirmation' => 'As senhas estão diferentes']);
                }
            }

            $user->updated_at = now();
            $user->name = $request->input('name');

            if ($user->email != $request->input('email')) {
                if (User::where('email', $request->input('email'))->count() < 1) {
                    $user->email = $request->input('email');
                } else {
                    return redirect()->back()->withInput()
                        ->withErrors(['email' => 'E-mail indisponível']);
                }
            }

            $user->save();

            if (isset($professor)) {
                $professor->name = $request->input('name');
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
