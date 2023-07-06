@extends('layouts.app')

@section('title')
Painel de Administração - Portal das Disciplinas
@endsection

@section('description')
Painel de Administração
@endsection

@section('content')

<div class="container">
    <div class="page-title">
        <h1>Configurações do tema</h1>
    </div>
    <div class="page-content" style="min-height: 60vh">
        <form method="POST" action="{{ route('configuracoes.store') }}" class="p-4" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="logo" class="form-label">Logotipo do projeto ou instituição</label></br>
                <input type="file" name="logo">
            </div>
            <div class="mb-3">
                <label for="logo_university" class="form-label">Logotipo da universidade</label></br>
                <input type="file" name="logo_university">
            </div>
            <div class="mb-3">
                <label for="favicon" class="form-label">Ícone do projeto ou instituição</label></br>
                <input type="file" name="favicon">
            </div>
            <div class="mb-3">
                <label for="banner" class="form-label">Imagem do banner na página da Disciplina</label></br>
                <input type="file" name="banner">
            </div>
            <div class="mb-3">
            <label for="PROJETO_SIGLA_SETOR" class="form-label">Cor #1 do efeito do banner da página da Disciplina</label>
            <input type="text" name="PROJETO_SIGLA_SETOR" id="PROJETO_SIGLA_SETOR" class="form-control" value="{{$theme['banner_color_hex_1']}}">
            </div>
            <div class="mb-3">
            <label for="PROJETO_SIGLA_SETOR" class="form-label">Cor #2 do efeito do banner da página da Disciplina</label>
            <input type="text" name="PROJETO_SIGLA_SETOR" id="PROJETO_SIGLA_SETOR" class="form-control" value="{{$theme['banner_color_hex_2']}}">
            </div>
            <div class="mb-3">
            <label for="PROJETO_SIGLA_SETOR" class="form-label">Sigla do Setor ou Instituição:</label>
            <input type="text" name="PROJETO_SIGLA_SETOR" id="PROJETO_SIGLA_SETOR" class="form-control" value="{{$theme['PROJETO_SIGLA_SETOR']}}">
            </div>
            <div class="mb-3">
            <label for="PROJETO_NOME_COMPLETO_SETOR" class="form-label">Nome da Instituição do projeto:</label>
            <input type="text" name="PROJETO_NOME_COMPLETO_SETOR" id="PROJETO_NOME_COMPLETO_SETOR" class="form-control" value="{{$theme['PROJETO_NOME_COMPLETO_SETOR']}}">
            </div>
            <div class="mb-3">
            <label for="PROJETO_CIDADE" class="form-label">Nome da cidade:</label>
            <input type="text" name="PROJETO_CIDADE" id="PROJETO_CIDADE" class="form-control" value="{{$theme['PROJETO_CIDADE']}}">
            </div>
            <div class="mb-3">
            <label for="PROJETO_SIGLA_SETOR_INSTITUICAO" class="form-label">Sigla do Setor e Institução:</label>
            <input type="text" name="PROJETO_SIGLA_SETOR_INSTITUICAO" id="PROJETO_SIGLA_SETOR_INSTITUICAO" class="form-control" value="{{$theme['PROJETO_SIGLA_SETOR_INSTITUICAO']}}">
            </div>
            <div class="mb-3">
            <label for="PROJETO_DISCIPLINAS_DESCRICAO" class="form-label">Descrição do projeto na página principal:</label>
            <input type="text" name="PROJETO_DISCIPLINAS_DESCRICAO" id="PROJETO_DISCIPLINAS_DESCRICAO" class="form-control" value="{{$theme['PROJETO_DISCIPLINAS_DESCRICAO']}}">
            </div>
            <div class="mb-3">
                <label for="primary-color" class="form-label">primary-color:</label>
                <input type="color" name="primary-color" id="primary-color" class="form-control" value="{{$theme['primary-color']}}">
            </div>
            <div class="mb-3">
                <label for="main-bg-color" class="form-label">main-bg-color:</label>
                <input type="color" name="main-bg-color" id="main-bg-color" class="form-control" value="{{$theme['main-bg-color']}}">
            </div>
            <div class="mb-3">
                <label for="main-md-color" class="form-label">main-md-color:</label>
                <input type="color" name="main-md-color" id="main-md-color" class="form-control" value="{{$theme['main-md-color']}}">
            </div>
            <div class="mb-3">
                <label for="main-title-bg-color" class="form-label">main-title-bg-color:</label>
                <input type="color" name="main-title-bg-color" id="main-title-bg-color" class="form-control" value="{{$theme['main-title-bg-color']}}">
            </div>
            <div class="mb-3">
                <label for="main-text-color" class="form-label">main-text-color:</label>
                <input type="color" name="main-text-color" id="main-text-color" class="form-control" value="{{$theme['main-text-color']}}">
            </div>
            <div class="mb-3">
                <label for="primary-color-darker" class="form-label">primary-color-darker:</label>
                <input type="color" name="primary-color-darker" id="primary-color-darker" class="form-control" value="{{$theme['primary-color-darker']}}">
            </div>
            <div class="mb-3">
                <label for="primary-color-lighter" class="form-label">primary-color-lighter:</label>
                <input type="color" name="primary-color-lighter" id="primary-color-lighter" class="form-control" value="{{$theme['primary-color-lighter']}}">
            </div>
            <div class="mb-3">
                <label for="secondary-color" class="form-label">secondary-color:</label>
                <input type="color" name="secondary-color" id="secondary-color" class="form-control" value="{{$theme['secondary-color']}}">
            </div>
            <div class="mb-3">
                <label for="secondary-color-darker" class="form-label">secondary-color-darker:</label>
                <input type="color" name="secondary-color-darker" id="secondary-color-darker" class="form-control" value="{{$theme['secondary-color-darker']}}">
            </div>
            <div class="mb-3">
                <label for="secondary-color-lighter" class="form-label">secondary-color-lighter:</label>
                <input type="color" name="secondary-color-lighter" id="secondary-color-lighter" class="form-control" value="{{$theme['secondary-color-lighter']}}">
            </div>
            <div class="mb-3">
                <label for="on-secondary" class="form-label">on-secondary:</label>
                <input type="color" name="on-secondary" id="on-secondary" class="form-control" value="{{$theme['on-secondary']}}">
            </div>
            <div class="mb-3">
                <label for="on-primary" class="form-label">on-primary:</label>
                <input type="color" name="on-primary" id="on-primary" class="form-control" value="{{$theme['on-primary']}}">
            </div>
            <button type="submit" class="btn btn-primary">Atualizar</button>
        </form>
    </div>

</div>



@endsection
