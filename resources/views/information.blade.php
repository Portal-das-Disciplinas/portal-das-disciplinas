@extends('layouts.app')
@section('title')
Sobre nós - Portal das Disciplinas IMD
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/about.css')}}">
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
                        <input class="d-none" id="fotoColaborador" name="foto" type='file' onchange="changeFileName()">
                        <small id="fileName">Nenhum arquivo selecionado</small>
                    </div>
                    <div class="form-group">
                        <label for="nomeColaborador">Nome</label>
                        <input id="nomeColaborador" name="nome" type=text class="form-control" placeholder="Nome e Sobrenome" required>
                    </div>
                    <div class="form-group">
                        <label for="emailColaborador">E-mail</label>
                        <input id="emailColaborador" name="email" type="email" class="form-control" placeholder="E-mail" required>
                    </div>
                    <div class="form-group">
                        <label for="vinculoColaborador">Vínculo</label>
                        <input id="vinculoColaborador" name="vinculo" type="text" class="form-control" placeholder="bolsista, voluntário..." required>
                    </div>
                    <div class="form-group">
                        <label for="funcaoColaborador">Função</label>
                        <input id="funcaoColaborador" name="funcao" type=text class="form-control" placeholder="Desenvolvedor, Designer, ...">
                    </div>
                    <div class="form-group">
                        <label for="lattesColaborador">Lattes</label>
                        <input id="lattesColaborador" name="lattes" type="text" class="form-control" placeholder="Endereço do currículo latttes">
                    </div>
                    <div class="form-group">
                        <label for="githubColaborador">Github</label>
                        <input id="githubColaborador" name="github" type="text" class="form-control" placeholder="Github">
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
                        <input name="value" type="text" class="form-control" placeholder="Título da seção" required>
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
                        <input name="value" type="text" class="form-control" placeholder="Título da seção" required>
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
                        <input name="value" type="text" class="form-control" placeholder="Título da seção" required>
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


<script>
    function changeFileName() {
        document.querySelector("#fileName").innerHTML = document.querySelector("#fotoColaborador").value;
    }
</script>
<!-- Styles -->
<div class='banner text-center d-flex align-items-center justify-content-center '>
    <h1 class='text-white'>Sobre & Colabore</h1>
</div>
@if($errors->any())
<h3 class="alert alert-danger text-center">
    {{$errors->first()}}
</h3>
@endif

<div class='container py-5' id="top-container">

    <div class='row'>
        <div class="col-md-5 p-text">
            <h2>O que é o Portal das Disciplinas</h2>
            <div class="row justify-content">
                <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px; margin-bottom: 8%">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE" allowfullscreen></iframe>
                </div>
            </div>
            <section class='our-team'>
                <h2>Nossa equipe</h2>
                <p class="text-justify mb-3">Veja ao lado os membros responsáveis por este portal.</p>
            </section>

            <div>
                <h2>Colabore</h2>
                <p class="text-justify mb-3">Caso tenha interesse em colaborar na adição de novas funcionalidades do site como sistema de classificações dinâmicos, refinamento de mecanismos de busca, interação com o portal de dados abertos da UFRN para recuperação de índices de aprovação de disciplinas, implementação de fóruns no portal, entre outros, por favor, entre em contato conosco.</p>
            </div>

            <div class="break-word">
                <b>Lista de emails para contato</b>
                <ul class="ml-3">
                    <li>eugenio@imd.ufrn.br</li>
                    <li>pedrogab96@gmail.com</li>
                    <li>victor_brandao@outlook.com</li>
                </ul>
            </div>
        </div>


        <div id="devsGrid" class="col-md-7 d-flex flex-column align-items-center">

            @if(Auth::user() && Auth::user()->isAdmin)
            <button id="showb" class="btn btn-success btn-sm mt-4 mb-4" data-toggle="modal" data-target="#modal-information" onclick="showModsal('modal-information')">Adicionar Colaborador</button>
            @endif
            <div class="info-collaborators-container mt-4">

                @if(Auth::user() && Auth::user()->isAdmin)
                <h2>{{$sectionNameManagers ?? "[Coordenadores]"}}</h2>
                <span data-toggle="modal" data-target="#modal-section-managers">editar</span>
                @else
                @if($hasManagers)
                <h2>{{$sectionNameManagers ?? ""}}</h2>
                @endif
                @endif
            </div>
            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
                @if($collaborator->active && $collaborator->isManager)
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4">
                    @component('components.info_contributors')
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
                        <a href="{{$link->url}}" class="smaller-p ml-1 mr-1">{{$link->name}}</a>
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
                <h2>{{$sectionNameCurrentCollaborators ?? "[Colaboradores Atuais]"}}</h2>
                <span data-toggle="modal" data-target="#modal-section-current">editar</span>
                @else
                @if($hasCurrentCollaborators)
                <h2>{{$sectionNameCurrentCollaborators ?? ""}}</h2>
                @endif
                @endif
            </div>

            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
                @if(!$collaborator->isManager && $collaborator->active)
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4">
                    @component('components.info_contributors')
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
                        <a href="{{$link->url}}" class="smaller-p ml-1 mr-1">{{$link->name}}</a>
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
                <h2>{{$sectionNameFormerCollaborators ?? "[Antigos Colaboradores]"}} </h2>
                <span data-toggle="modal" data-target="#modal-section-formers">editar</span>
                @else
                @if($hasFormerCollaborators)
                <h2>{{$sectionNameFormerCollaborators ?? ""}} </h2>
                @endif
                @endif
            </div>
            @if(true)
            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
                @if(!$collaborator->active )
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4">
                    @component('components.info_contributors')
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
                        <a href="{{$link->url}}" class="smaller-p ml-1 mr-1">{{$link->name}}</a>
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
            @endif
        </div>
        <!--<div id="devsGrid" class="col-md-7 d-flex flex-column align-items-center">



        </div> -->
    </div>

    <!--
    <section>
        <h3 class='mb-3'>Colaboradores Anteriores</h3>
        <hr>
        <div class="row mt-5">
            <div class="col-md-3 d-flex flex-column align-items-center">

                <img class="fluid clip-path smaller-image" src="img/user2.png" alt="foto-dev">
                <p><strong> Nome do colaborador </strong></p>
                <p> Descrição </p>
                <p> 12/12/2021 - 12/12/2021</p>
            </div>
            <div class="col-md-3 d-flex flex-column align-items-center">

                <img class="fluid clip-path smaller-image" src="img/user2.png" alt="foto-dev">
                <p><strong> Nome do colaborador </strong></p>
                <p> Descrição </p>
                <p> 12/12/2021 - 12/12/2021</p>
            </div>
            <div class="col-md-3 d-flex flex-column align-items-center">

                <img class="fluid clip-path smaller-image" src="img/user2.png" alt="foto-dev">
                <p><strong> Nome do colaborador </strong></p>
                <p> Descrição </p>
                <p> 12/12/2021 - 12/12/2021</p>
            </div>
            <div class="col-md-3 d-flex flex-column align-items-center">

                <img class="fluid clip-path smaller-image" src="img/user2.png" alt="foto-dev">
                <p><strong> Nome do colaborador </strong></p>
                <p> Descrição </p>
                <p> 12/12/2021 - 12/12/2021</p>
            </div>
        </div>
    </section>
-->



</div>
<script>
    links = [];

    function renderLinks() {
        var html = "";
        links.forEach(function(link, i) {
            html += "<div class='mb-4'>" +
                "<input class=' mb-1 form-control' name='linkName[]' type='text' placeholder='Twitter, Instagram, Facebook, etc...' value='" + link.name + "'>" +
                "<input class='form-control' name='linkUrl[]' type='text' placeholder='Url do link' value='" + link.url + "'>" +
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
</script>

@endsection