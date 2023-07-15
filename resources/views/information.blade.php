@extends('layouts.app')


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
                        <input id="vinculoColaborador" name="vinculo" type="text" class="form-control" value="{{old('vinculo')}}"placeholder="bolsista, voluntário..." required>
                    </div>
                    <div class="form-group">
                        <label for="funcaoColaborador">Função</label>
                        <input id="funcaoColaborador" name="funcao" type=text class="form-control" value="{{old('funcao')}}"placeholder="Desenvolvedor, Designer, ...">
                    </div>
                    <div class="form-group">
                        <label for="lattesColaborador">Lattes</label>
                        <input id="lattesColaborador" name="lattes" type="url" class="form-control" value="{{old('lattes')}}" placeholder="https://">
                    </div>
                    <div class="form-group">
                        <label for="githubColaborador">Github</label>
                        <input id="githubColaborador" name="github" type="url" class="form-control" value="{{old('github')}}"placeholder="https://">
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

<div id="modal-video-producers" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
             <div class="modal-header">
                 <h3 class="modal-title">Produtores do vídeo</h3>
             </div>
             <div class="modal-body">
                 <div id="formVideoContentProducers"class="form">
                 </div>
                 <form id="formContentProducersJson" method="post" action="{{route('content_producers.store_update')}}">
                    @csrf
                    <input name="contentProducers" type='hidden'>
                 </form>
                 <button id="btnAddParticipant"class="btn btn-primary" onclick="addParticipantField()">Adicionar Produtor</button>
             </div>
             <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary btn-sm" for="btnAddParticipant" onclick="submitFormContentProducers()">Salvar</button>

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
            <div class="row">
                <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE" allowfullscreen></iframe>
                </div>
            </div>
            <div class="row mb-5 d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <b class="pl-1"data-toggle="collapse" data-target="#collapseCreditos">
                        créditos <li class="fa fa-caret-down"></li>
                    </b>
                    <span class="text-primary" style="cursor:pointer" onclick="openModalVideoProducers()">editar</span>
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
                    <label class="text-primary" style="cursor:pointer"onclick="$('#modal-section-collaborate').modal('show')">editar</label>
                    @endif
                </div>
                @if(($sectionCollaborateTitle == "") && Auth::user() && Auth::user()->isAdmin)
                <p class="text-secondary"><i>[Não há conteúdo para essa seção]</i></p>
                @endif
                <p style="white-space:pre-wrap;" id="sectionCollaborateText" class="text-justify mb-3">{{$sectionCollaborateText}}</p>
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
</script>

<script>
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
</script>

<script>
    let databaseVideoContentProducers = @json($videoAboutProducers);
    let videoContentProducers = @json($videoAboutProducers);// usado no modal
</script>

@endsection

@section('scripts-bottom')
    <script src="{{asset('js/about.js')}}"></script>
@endsection