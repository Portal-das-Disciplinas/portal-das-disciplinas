@extends('layouts.app')


@section('styles-head')
<link rel="stylesheet" href="{{asset('css/about.css')}}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endsection
@section('content')
<div id="modal-information" class="modal fade" tabindex="-1" role='dialog'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Cadastro de Colaborador</h2>
            </div>
            <div class="modal-body">
                <form id="collaborators-form" class="form" action="{{route('collaborators.store')}}" enctype="multipart/form-data" method='post'>
                    @csrf
                    <div class="form-group">
                        <label class="btn btn-outline-info" for="fotoColaborador" name="foto">Adicionar Foto</label>
                        <input class="d-none" id="fotoColaborador" name="foto" type='file' value="{{old('foto')}}" onchange="changeFileName()">
                        <small id="fileName">Nenhum arquivo selecionado</small>
                    </div>
                    <div class="form-group">
                        <label for="nomeColaborador">Nome</label>
                        <input id="nomeColaborador" name="nome" type=text class="form-control" value="{{old('nome')}}" placeholder="Nome e Sobrenome" required>
                    </div>
                    <div class="form-group">
                        <label for="emailColaborador">E-mail</label>
                        <input id="emailColaborador" name="email" type="email" class="form-control" value="{{old('email')}}" placeholder="e-mail">
                    </div>
                    <div class="form-group">
                        <label for="vinculoColaborador">Vínculo</label>
                        <input id="vinculoColaborador" name="vinculo" type="text" class="form-control" value="{{old('vinculo')}}" placeholder="bolsista, voluntário..." required>
                    </div>
                    <div class="form-group">
                        <label for="funcaoColaborador">Função</label>
                        <input id="funcaoColaborador" name="funcao" type=text class="form-control" value="{{old('funcao')}}" placeholder="Desenvolvedor, Designer, ...">
                    </div>
                    <div class="form-group">
                        <label for="lattesColaborador">Lattes</label>
                        <input id="lattesColaborador" name="lattes" type="url" class="form-control" value="{{old('lattes')}}" placeholder="https://">
                    </div>
                    <div class="form-group">
                        <label for="githubColaborador">Github</label>
                        <input id="githubColaborador" name="github" type="url" class="form-control" value="{{old('github')}}" placeholder="https://">
                    </div>
                    <hr class="hr">

                    <div>
                        <label class="text-primary">Links</label>
                        <div id="links" class="mb-0">
                            <!-- Conteúdo gerando por javascript: function renderLinks() -->
                        </div>
                        <label class="btn btn-info text-white mt-0" onclick="addLinkField()">Adicionar Link</label>
                    </div>
                    <div class=" d-flex justify-content-start align-items-baseline">
                        <input id="colaboradorAtivo" class="mr-2" name="ativo" type="checkbox" checked>
                        <label for="colaboradorAtivo">Ativo</label>
                    </div>
                    <div class="d-flex justify-content-start align-items-baseline">
                        <input class="mr-2" id="coordenador" name="coordenador" type="checkbox">
                        <label for="coordenador">Coordenador</label>
                    </div>
                    <input id="btn-cadastrar" type="submit" hidden>
                    <div class="container">
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label>Data de entrada</label>
                                <input name="joinDate" id="joinDate" type="date" class="form-control">
                            </div>
                            <div class="form-group col-sm-6">
                                <label>Data de saída</label>
                                <input name="leaveDate" id="leaveDate" type="date" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <label class="btn btn-secondary" data-dismiss="modal">Fechar</label>
                    <label class="btn btn-primary" for="btn-cadastrar">Cadastrar</label>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="modal-section-collaborate" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edição da seção colabore</h3>
            </div>
            <div class="modal-body">
                <div id="errors" class="alert alert-danger alert-dismissible fade d-none" role="alert">
                    <strong>Ocorreram erros ao cadastrar</strong>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="section-collaborate-form-title" class="form" action="{{route('information.supdate')}}" method="POST" onsubmit="submitCollaborateSection(event)">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Título</label>
                        <input name="name" type="text" hidden value="sectionCollaborateTitle">
                        <input id="collaborateTitleInput" name="value" class="form-control" type="text" placeholder="Título para a seção" value="{{$sectionCollaborateTitle}}" required>
                    </div>
                </form>
                <form id="section-collaborate-form-text" class="form" action="{{route('information.supdate')}}" method="POST">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label>Conteúdo para a seção</label>
                        <input name="name" type="text" hidden value="sectionCollaborateText">
                        <textarea id="collaborateTextInput" name="value" rows=10 class="form-control">{{$sectionCollaborateText}}</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal" data-target="#modal-section-collaborate">Fechar</button>
                <button class="btn btn-primary" onclick="updateSectionCollaborate(event)">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-section-managers" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Título para a seção</h2>
            </div>
            <div class="modal-body">
                <form class="form" id="form-current-managers" action="{{route('information.supdate')}}" method="post">
                    @method('POST')
                    @csrf
                    <div class="form-group">
                        <input id="section-name" name="value" type="text" class="form-control" placeholder="Título da seção" maxlength="74" required>
                    </div>
                    <input name="name" type="hidden" value="sectionNameManagers">
                    <input id="btn-modal-section-managers" class="btn btn-success" type="submit" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <label for="btn-modal-section-managers" class="btn btn-primary">Cadastrar</label>
            </div>
        </div>
    </div>
</div>


<div id="modal-section-current" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Título para a seção</h3>
            </div>
            <div class="modal-body">
                <form id="form-current-collaborators" class="form" action="{{route('information.supdate')}}" method="post">
                    @method('POST')
                    @csrf
                    <div class="form-group">
                        <input id="section-name" name="value" type="text" class="form-control" placeholder="Título da seção" maxlength="74" required>
                    </div>
                    <input name="name" type="hidden" value="sectionNameCurrentCollaborators">
                    <input id="btn-modal-section-current" type="submit" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <label for="btn-modal-section-current" class="btn btn-primary">Cadastrar</label>
            </div>
        </div>
    </div>
</div>

<div id="modal-section-formers" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Título para a seção</h2>
            </div>
            <div class="modal-body">
                <form id="form-former-collaborators" class="form" action="{{route('information.supdate')}}" method="post">
                    @method('POST')
                    @csrf
                    <div class="form-group">
                        <input id="section-name" name="value" type="text" class="form-control" placeholder="Título da seção" maxlength="74" required>
                    </div>
                    <input name="name" type="hidden" value="sectionNameFormerCollaborators">
                    <input id="btn-modal-section-formers" type="submit" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <label for="btn-modal-section-formers" class="btn btn-primary">Cadastrar</label>
            </div>
        </div>
    </div>
</div>

<div id="modal-video-producers" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Produtores do vídeo</h3>
            </div>
            <div class="modal-body">
                <div id="formVideoContentProducers" class="form">
                </div>
                <form id="formContentProducersJson" method="post" action="{{route('content_producers.store_update')}}">
                    @csrf
                    <input name="contentProducers" type='hidden'>
                </form>
                <button id="btnAddParticipant" class="btn btn-outline-primary btn-sm" onclick="addParticipantField()">Adicionar campo</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary btn-sm" for="btnAddParticipant" onclick="submitFormContentProducers()">Salvar Produtores</button>

            </div>
        </div>
    </div>
</div>

<div id="modalAlterarVideo" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Alteração de vídeo</h3>
            </div>
            <div class="modal-body">
                <form class="form" method="post" action="{{route('information.supdate')}}">
                    @csrf
                    <input type="hidden" name="name" value="linkVideoPortal">
                    <input class="form-control" name="value" type="url" required placeholder="URL do link" onchange="onChangeInputLink()">
                    <small class="d-none text-danger">* URL inválida</small>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary btn-sm" onclick="updateVideoPortal()">Salvar</button>
            </div>
        </div>
    </div>
</div>

<div id="modalCollaboratorProduction" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Produção do desenvolvedor no portal</h3>
            </div>
            <div class="modal-body">
                <p style="word-wrap: break-word;"><strong id="productionBrief"></strong></p>
                <p id="productionDetails" class="text-secondary" style="word-wrap: break-word;"></p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" data-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>

<div id="modalCreateCollaboratorProductions" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Produção do desenvolvedor no portal</h3>
            </div>
            <div class="modal-body">
                <form id="formCollaboratorProductionsCreate" action="{{route('colalborators_productions.store_list_json')}}" method="POST">
                    @csrf
                    <div id="fields"></div>
                    <input id="productionCollaboratorId" name="productionCollaboratorId" hidden>
                    <input id="collaboratorProductionsJSON" name="collaboratorProductionsJSON" hidden>
                    <div class="d-flex justify-content-end">
                        <button id="btnSubmitProductions" type="submit" class="btn btn-success" onclick="btnSaveProductions()">Salvar</button>
                    </div>
                </form>
                <button class="btn btn-outline-primary btn-sm" onclick="addField('formCollaboratorProductionsCreate')">Adicionar Campo</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>

<!-- Styles -->
<div class='banner text-center d-flex align-items-center justify-content-center '>
    <h1 class='text-white'>Sobre & Colabore</h1>
</div>
@if($errors->any())
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <div class="d-flex justify-content-center">
        <div>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
        </div>
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class='container py-5' id="top-container">

    <div class='row'>
        <div class="col-md-5 p-text">
            <h2 class="mb-5">O que é o Portal das Disciplinas</h2>
            <div class="row-fluid">
                @if(Auth::user() && Auth::user()->isAdmin)
                <div class="d-flex justify-content-end">
                    <span class="text-primary" style="cursor:pointer" onclick="$('#modalAlterarVideo').modal('show')">alterar</span>
                    <form id="deleteVideoForm" method='post' action="{{route('information.deleteByName','linkVideoPortal')}}">
                        @csrf
                        @method('delete')
                        <span class="text-danger ml-4" style="cursor:pointer" onclick="removeVideo()">remover</span>
                    </form>
                </div>
                @endif

                @if(isset($videoUrl))
                <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px;">
                    <iframe class="embed-responsive-item" src="{{$videoUrl ?? null}}" allowfullscreen></iframe>
                </div>
                @else
                @if(Auth::user() && Auth::user()->isAdmin)
                <div class="d-flex justify-content-center">
                    <h1 class="text-secondary p-5"> -NÃO HÁ VÍDEO- </h1>
                </div>
                @endif
                @endif
            </div>
            <div class="row-fluid mb-5 d-flex flex-column">
                <div class="d-flex justify-content-between">
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <b class="pl-0" style="cursor:pointer;" data-toggle="collapse" data-target="#collapseCreditos">
                        @if(count($videoAboutProducers) >0)
                        créditos <li name="caret-icon" class="fa fa-caret-down"></li>
                        @else
                        <span class='text-secondary'>Não há créditos</span>
                        @endif
                    </b>
                    @endif
                    @guest
                    @if(count($videoAboutProducers)>0 && (isset($videoUrl)))
                    <b class="pl-0" style="cursor:pointer" data-toggle="collapse" data-target="#collapseCreditos">
                        créditos <li name="caret-icon" class="fa fa-caret-down"></li>
                    </b>
                    @endif
                    @endguest
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <span class="text-primary" style="cursor:pointer" onclick="openModalVideoProducers()">editar</span>
                    @endif
                </div>
                <div id="collapseCreditos" class="collapse pl-1">
                    <div class="d-flex flex-column" style="line-height:1.5">
                        @foreach($videoAboutProducers as $producer)
                        <small class="">
                            <a href="mailto:{{$producer->email}}" style="color:black">
                                {{$producer->name}}
                            </a>
                        </small>
                        @endforeach

                    </div>
                </div>

            </div>
            <section class='our-team p-0'>
                <h2>Nossa equipe</h2>
                <p class="text-justify mb-3">Veja ao lado os membros responsáveis por este portal.</p>
            </section>

            <div>
                <div class="d-flex align-items-baseline justify-content-between">
                    @if($sectionCollaborateTitle != "")
                    <h2 id="sectionCollaborateTitle">{{$sectionCollaborateTitle}}</h2>
                    @endif
                    @if(Auth::user() && Auth::user()->isAdmin)
                    @if($sectionCollaborateTitle == "")
                    <p class="text-secondary"><i>[Sem título para a seção]</i></p>
                    @endif
                    <label class="text-primary" style="cursor:pointer" onclick="$('#modal-section-collaborate').modal('show')">editar</label>
                    @endif
                </div>
                @if(($sectionCollaborateTitle == "") && Auth::user() && Auth::user()->isAdmin)
                <p class="text-secondary"><i>[Não há conteúdo para essa seção]</i></p>
                @endif
                <p style="white-space:pre-wrap;" id="sectionCollaborateText" class="text-justify mb-3">{{$sectionCollaborateText}}</p>
            </div>
        </div>


        <div id="devsGrid" class="d-flex col-md-7 flex-column align-items-center">

            @if(Auth::user() && Auth::user()->isAdmin)
            <button id="showb" class="btn btn-success btn-sm mt-4 mb-4" data-toggle="modal" data-target="#modal-information">Adicionar Colaborador</button>
            @endif
            <div class="info-collaborators-container mt-4">

                @if(Auth::user() && Auth::user()->isAdmin)
                <h2 style="text-align:center">{{$sectionNameManagers ?? "[Coordenadores]"}}</h2>
                <span onclick="openModalEditSectionName('modal-section-managers', '{{$sectionNameManagers}}')">editar</span>
                @else
                @if($hasManagers)
                <h2 style="text-align:center">{{$sectionNameManagers ?? ""}}</h2>
                @endif
                @endif
            </div>
            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
                @if($collaborator->active && $collaborator->isManager)
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4 mx-4">
                    @component('components.info_contributors')
                    @slot('id') {{$collaborator->id}} @endslot
                    @slot('productions') {{$collaborator->productions}} @endslot
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') $collaborator->name @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('links')
                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                        @foreach($collaborator->links as $link)
                        <img src="https://www.google.com/s2/favicons?domain={{$link->url}}" alt="icone">
                        <a href="{{$link->url}}" class="smaller-p ml-1 mr-1" rel="noopener" target="_blank">{{$link->name}}</a>
                        @endforeach
                    </div>
                    @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                    @slot('idCollaborator') {{$collaborator->id}} @endslot
                    @endcomponent
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <div class="d-flex">
                        <a href="collaborators/{{$collaborator->id}}/edit" class="mr-2">Editar</a>
                        <form action="collaborators/{{$collaborator->id}}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger btn-sm align-text-bottom ml-2" value="remover">
                        </form>
                    </div>
                    @endif
                </div>
                @endif
                @endforeach

            </div>

            <div class="info-collaborators-container mt-4">
                @if(Auth::user() && Auth::user()->isAdmin)
                <h2 style="text-align:center">{{$sectionNameCurrentCollaborators ?? "[Colaboradores Atuais]"}}</h2>
                <span onclick="openModalEditSectionName('modal-section-current','{{$sectionNameCurrentCollaborators}}')">editar</span>
                @else
                @if($hasCurrentCollaborators)
                <h2 style="text-align:center">{{$sectionNameCurrentCollaborators ?? ""}}</h2>
                @endif
                @endif
            </div>

            <div class="d-flex flex-wrap justify-content-around mt-4 w-100">
                @foreach($collaborators as $collaborator)

                @if(!$collaborator->isManager && $collaborator->active)
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4 mx-0" style="width:300px">
                    @component('components.info_contributors')
                    @slot('id') {{$collaborator->id}} @endslot
                    @slot('productions')
                    <div class="d-flex flex-row align-items-center justify-content-center" style="cursor:pointer">
                        @if(count($collaborator->productions) > 0)
                        <small id="toggleCollapse-{{$collaborator->id}}" class="text-secondary" aria-expanded="true" onclick="expandCollapseProductions(event)" data-toggle="collapse" data-target="#collapse{{$collaborator->id}}" role="button">
                            Produções no portal
                        </small>
                        <span class="material-symbols-outlined text-secondary" style="cursor:default">expand_more</span>
                        @endif
                        @if(Auth::user() && Auth::user()->isAdmin)
                        <button class="btn btn-success btn-sm mb-3 mt-1" onclick="showModalCreateCollaboratorProductions('{{$collaborator->id}}')">
                            @if(count($collaborator->productions) > 0)
                            &nbsp;+&nbsp;
                            @else
                            Adicionar produção
                            @endif
                        </button>
                        @endif
                    </div>
                    <div id="collapse{{$collaborator->id}}" class="collapse bg-white justify-content-start align-items-start mx-1" style="box-shadow:5px 5px 10px grey;border-radius:5px;">
                        <table class="table table-striped" style="table-layout:fixed;width:100%">
                            <tbody>
                                @foreach($collaborator->productions->sortBy([['created_at','desc'],['id','asc']])->take(5) as $production)
                                <tr>
                                    <td class="py-3" onclick="showModalCollaboratorProduction('{{$production->brief}}','{{$production->details}}')" style="cursor:pointer">
                                        <small>
                                            <p style="line-height:1.1; text-align:center; word-wrap:break-word">{{$production->brief}}</p>
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                                @if((count($collaborator->productions))> 5 || (Auth::user() && Auth::user()->isAdmin && (count($collaborator->productions) > 0)))
                                <tr>
                                    <td class="py-3" style="cursor:pointer">
                                        <a href="{{route('collaborator_productions.show',$collaborator->id)}}" class="nav-link">
                                            @if((Auth::user() && Auth::user()->isAdmin))
                                            Ver mais ou Editar
                                            @else
                                            Ver mais
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endslot
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') {{$collaborator->name}} @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('links')
                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                        @foreach($collaborator->links as $link)
                        <img src="https://www.google.com/s2/favicons?domain={{$link->url}}" alt="icone">
                        <a href="{{$link->url}}" class="smaller-p ml-1 mr-1" rel="noopener" target="_blank">{{$link->name}}</a>
                        @endforeach
                    </div>
                    @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                    @slot('idCollaborator') {{$collaborator->id}} @endslot
                    @endcomponent

                    @if(Auth::user() && Auth::user()->isAdmin)
                    <div class="d-flex">
                        <a href="collaborators/{{$collaborator->id}}/edit" class="mr-2">Editar</a>
                        <form action="collaborators/{{$collaborator->id}}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger btn-sm align-text-bottom ml-2" value="remover">
                        </form>
                    </div>
                    @endif
                </div>
                @endif
                @endforeach

            </div>


            <div class="info-collaborators-container mt-4">
                @if(Auth::user() && Auth::user()->isAdmin)
                <h2 style="text-align:center">{{$sectionNameFormerCollaborators ?? "[Antigos Colaboradores]"}} </h2>
                <span onclick="openModalEditSectionName('modal-section-formers','{{$sectionNameFormerCollaborators}}')">editar</span>
                @else
                @if($hasFormerCollaborators)
                <h2 style="text-align:center">{{$sectionNameFormerCollaborators ?? ""}} </h2>
                @endif
                @endif
            </div>

            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
                @if(!$collaborator->active )
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4" style="width:300px">
                    @component('components.info_contributors')
                    @slot('id') {{$collaborator->id}} @endslot
                    @slot('productions')
                    <div class="d-flex flex-row align-items-center justify-content-center" style="cursor:pointer">
                        @if(count($collaborator->productions) > 0)
                        <small id="toggleCollapse-{{$collaborator->id}}" class="text-secondary" aria-expanded="true" onclick="expandCollapseProductions(event)" data-toggle="collapse" data-target="#collapse{{$collaborator->id}}" role="button">
                            Produções no portal
                        </small>
                        <span class="material-symbols-outlined text-secondary" style="cursor: default">expand_more</span>
                        @endif
                        @if(Auth::user() && Auth::user()->isAdmin)
                        <button class="btn btn-success btn-sm mb-3 mt-1" onclick="showModalCreateCollaboratorProductions('{{$collaborator->id}}')">
                            @if(count($collaborator->productions) > 0)
                            &nbsp;+&nbsp;
                            @else
                            Adicionar produção
                            @endif
                        </button>
                        @endif
                    </div>
                    <div id="collapse{{$collaborator->id}}" class="collapse bg-white justify-content-start align-items-start mx-1" style="box-shadow:5px 5px 10px grey;border-radius:5px;">
                        <table class="table table-striped">
                            <tbody>
                                @foreach($collaborator->productions->sortBy([['created_at','desc'],['id','asc']])->take(5) as $production)
                                <tr>
                                    <td class="py-3" onclick="showModalCollaboratorProduction('{{$production->brief}}','{{$production->details}}')" style="cursor:pointer">
                                        <small>
                                            <p style="line-height:1.1; text-align:center">{{$production->brief}}</p>
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                                @if((count($collaborator->productions))> 5 || (Auth::user() && Auth::user()->isAdmin && (count($collaborator->productions) > 0)))
                                <tr>
                                    <td class="py-3" style="cursor:pointer">
                                    <a href="{{route('collaborator_productions.show',$collaborator->id)}}" class="nav-link">
                                            @if((Auth::user() && Auth::user()->isAdmin))
                                            Ver mais ou Editar
                                            @else
                                            Ver mais
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    @endslot
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('period')
                    @if($collaborator->joinDate && $collaborator->leaveDate)
                    {{
                        date_format(date_create($collaborator->joinDate),'Y') ." - ". date_format(date_create($collaborator->leaveDate),'Y')
                    }}
                    @endif
                    @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') $collaborator->name @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('links')
                    <div class="d-flex justify-content-center align-items-center flex-wrap">
                        @foreach($collaborator->links as $link)
                        <img src="https://www.google.com/s2/favicons?domain={{$link->url}}" alt="icone">
                        <a href="{{$link->url}}" class="smaller-p ml-1 mr-1" rel="noopener" target="_blank">{{$link->name}}</a>
                        @endforeach
                    </div>
                    @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                    @endcomponent
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <div class="d-flex">
                        <a href="collaborators/{{$collaborator->id}}/edit" class="mr-2">Editar</a>
                        <form action="collaborators/{{$collaborator->id}}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger btn-sm align-text-bottom ml-2" value="remover">
                        </form>
                    </div>
                    @endif
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>


</div>
@endsection

@section('scripts-bottom')
<script>
    function changeFileName() {
        document.querySelector("#fileName").innerHTML = document.querySelector("#fotoColaborador").value;
    }

    function updateSectionCollaborate(event) {
        $.ajax({
            url: "{{route('information.supdate')}}",
            method: 'POST',
            data: $('#section-collaborate-form-title').serialize(),
            error: function(e) {
                document.querySelector('#modal-section-collaborate #errors').classList.remove('d-none');
                document.querySelector('#modal-section-collaborate #errors').classList.add('show');
            },
            success: function(result) {
                $.ajax({
                    url: "{{route('information.supdate')}}",
                    method: 'POST',
                    data: $('#section-collaborate-form-text').serialize(),
                    success: function(result) {
                        $('#modal-section-collaborate').modal('hide')
                        window.location.href = "{{route('information')}}";
                    },
                    error: function(e) {
                        document.querySelector('#modal-section-collaborate #errors').classList.remove('d-none');
                        document.querySelector('#modal-section-collaborate #errors').classList.add('show');
                    }
                });
            }

        })

    }

    links = [];

    function renderLinks() {
        var html = "";
        links.forEach(function(link, i) {
            html += "<div class='mb-4'>" +
                "<input class=' mb-1 form-control' name='linkName[]' type='text' placeholder='Twitter, Instagram, Facebook, etc...' required value='" + link.name + "'>" +
                "<input class='form-control' name='linkUrl[]' type='url' placeholder='https://' required value='" + link.url + "'>" +
                "<label id = '" + link.id + "'class='btn btn-link text-danger' onclick='deleteLinkField(" + i + ")'>remover</label>" +
                "</div>"
        });
        return html;
    }

    function addLinkField() {

        linkNames = document.querySelectorAll("input[name='linkName[]']");
        linkUrls = document.querySelectorAll("input[name='linkUrl[]']");

        for (var i = 0; i < linkNames.length; i++) {
            links[i].name = linkNames[i].value;
            links[i].url = linkUrls[i].value;
        }

        links.push({
            name: "",
            url: ""
        });
        document.querySelector('#links').innerHTML = renderLinks();
    }

    function deleteLinkField(index) {

        linkNames = document.querySelectorAll("input[name='linkName[]']");
        linkUrls = document.querySelectorAll("input[name='linkUrl[]']");

        for (var i = 0; i < linkNames.length; i++) {
            links[i].name = linkNames[i].value;
            links[i].url = linkUrls[i].value;
        }

        links = links.filter(function(link, i) {
            if (index != i) {
                return link;
            }
        });

        document.querySelector('#links').innerHTML = renderLinks();
    }

    function expandCollapseProductions(event) {
        let expandIcon = document.querySelector("#" + event.target.id).nextElementSibling;
        if (expandIcon.innerHTML == "expand_less") {
            expandIcon.innerHTML = "expand_more";
        } else if (expandIcon.innerHTML == "expand_more") {
            expandIcon.innerHTML = "expand_less";
        }


    }

    let databaseVideoContentProducers = @json($videoAboutProducers);
    let videoContentProducers = @json($videoAboutProducers); // usado no modal
</script>

<script src="{{asset('js/about.js')}}"></script>
<script src="{{asset('js/collaboratorProductions.js')}}"></script>
@endsection