<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

/**
 * @class ThemeController
 * @brief Controlador para gerenciar o tema da aplicação.
 */
class ThemeController extends Controller
{
    protected $theme;

    public function __construct()
    {
        $contents = Storage::get('theme/theme.json');
        $this->theme = json_decode($contents, true);
    }

    /**
     * @brief Exibe a página de configuração do tema.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\View\View Retorna a view 'admin.theme.index'.
     */
    public function index(Request $request)
    {
        return view('admin.theme.index')
        ->with('theme', $this->theme);
    }
    
    /**
     * @brief Armazena as configurações do tema.
     * @param Request $request Objeto que contém as informações da requisição HTTP.
     * @return \Illuminate\Http\RedirectResponse Redireciona para a página de configurações.
     */
    public function store(Request $request)
    {
        $logo = $request->file('logo');
        $logo_university =  $request->file('logo_university');
        $favicon =  $request->file('favicon');
        $banner =  $request->file('banner');

        if ($logo) {
            if (Storage::exists('public/img/logo')) {
                Storage::delete('public/img/logo');
            }
            $path = $logo->storeAs('public/img', 'logo.png');
        }

        if ($logo_university) {
            if (Storage::exists('public/img/logo_university')) {
                Storage::delete('public/img/logo_university');
            }
            $path = $logo_university->storeAs('public/img', 'logo_university.png');
        }

        if ($favicon) {
            if (Storage::exists('public/img/favicon')) {
                Storage::delete('public/img/favicon');
            }
            $path = $favicon->storeAs('public/img', 'favicon.ico');
        }

        if ($banner) {
            if (Storage::exists('public/img/banner')) {
                Storage::delete('public/img/banner');
            }
            $path = $banner->storeAs('public/img', 'banner.png');
        }

        $contents = Storage::get('theme/theme.json');

        // Decode the JSON content
        $config = json_decode($contents, true);
        
        $themeInputs = [
            'PROJETO_SIGLA_SETOR',
            'PROJETO_NOME_COMPLETO_SETOR',
            'PROJETO_CIDADE',
            'PROJETO_SIGLA_SETOR_INSTITUICAO',
            'PROJETO_DISCIPLINAS_DESCRICAO',
            'primary-color',
            'main-bg-color',
            'main-md-color',
            'main-title-bg-color',
            'main-text-color',
            'primary-color-darker',
            'primary-color-lighter',
            'secondary-color',
            'secondary-color-darker',
            'secondary-color-lighter',
            'on-secondary',
            'on-primary'
        ];
        
        foreach ($themeInputs as $input) {
            $config[$input] = $request->input($input);
        }

        // Encode the modified configuration back to JSON
        $updatedContents = json_encode($config);

        // Save the updated contents to the theme configuration file
        Storage::put('theme/theme.json', $updatedContents);

        return Redirect::route('configuracoes.index');
    }
}
