@extends('layouts.app')
@section('title')
Sobre nós - Portal das Disciplinas IMD
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/about.css')}}">
@endsection

@section('content')
<div id="modal-information" class="modal-information modal-information-invisible">
    <div class="content">
        <h3>Cadastro de Colaborador</h3>
        <form id="collaborators-form" action="{{route('collaborators.store')}}" enctype="multipart/form-data" method='post'>
            @csrf
            <label for="fotoColaborador" name="foto">Foto</label>
            <input id="fotoColaborador" name="foto" type='file'>
            <label for="nomeColaborador">Nome</label>
            <input id="nomeColaborador" name="nome" type=text class="form-control" placeholder="Nome e Sobrenome" required>
            <label for="emailColaborador">e-mail</label>
            <input id="emailColaborador" name="email" type="email" class="form-control" placeholder="E-mail" required>
            <label for="vinculoColaborador">Vínculo</label>
            <input id="vinculoColaborador" name="vinculo" type="text" class="form-control" placeholder="bolsista, voluntário..." required>
            <label for="funcaoColaborador">Função</label>
            <input id="funcaoColaborador" name="funcao" type=text class="form-control" placeholder="Desenvolvedor, Designer, ...">
            <label for="lattesColaborador">Lattes</label>
            <input id="lattesColaborador" name="lattes" type="text" class="form-control" placeholder="Endereço do currículo latttes">
            <label for="githubColaborador">Github</label>
            <input id="githubColaborador" name="github" type="text" class="form-control" placeholder="Github">
            <div>
                <label for="colaboradorAtivo">Ativo</label>
                <input id="colaboradorAtivo" name="ativo" type="checkbox" checked>
            </div>
            <div>
                <label for="coordenador">Coordenador</label>
                <input id="coordenador" name="coordenador" type="checkbox">
            </div>
            <div class="buttons">
                <button id="btn-fechar" onclick="closeModal(event,'modal-information')" class="btn btn-info">Fechar</button>
                <button id="btn-cadastrar" onclick="submitEvent(event, 'modal-information','collaborators-form')" class="btn btn-success" type="submit">Cadastrar</button>
            </div>
        </form>
    </div>

</div>

<div id="modal-texto-colaboradores1" class="modal-information modal-information-invisible">
    <div class="content">
        <h3>Título para as seções colaboradores atuais</h3>
        <form id="form-texto-colaboradores1"action="{{route('information.store')}}" method="post">
            @method('POST')
            @csrf
            <input name="information-value" type="text" class="form-control" placeholder="Seção colaboradores atuais (Título)" required>
            <input type="hidden" name="id-information" value="{{$idcurrent}}">
            <input type ="hidden" name="information-name" value="sectionNameCurrentCollaborators">
            <div class="buttons">
                <button onclick="closeModal(event,'modal-texto-colaboradores1')" class=" btn btn-info">Fechar</button>
                <button onclick="submitEvent(event,'modal-texto-colaboradores1','form-texto-colaboradores1')" class="btn btn-success" type="submit">Cadastrar</button>
            </div>
        </form>

    </div>
</div>

<div id="modal-texto-colaboradores2" class="modal-information modal-information-invisible">
    <div class="content">
        <h3>Título para as seções colaboradores antigos</h3>
        <form id="form-texto-colaboradores2"action="{{route('information.store')}}" method="post">
            @method('POST')
            @csrf
            <input name="information-value" type="text" class="form-control" placeholder="Seção colaboradores antigos (Título)" required>
            <input type="hidden" name="id-information" value="{{$idformer}}">
            <input type ="hidden" name="information-name" value="sectionNameFormerCollaborators">
            <div class="buttons">
                <button onclick="closeModal(event,'modal-texto-colaboradores2')" class=" btn btn-info">Fechar</button>
                <button onclick="submitEvent(event,'modal-texto-colaboradores2','form-texto-colaboradores2')" class="btn btn-success" type="submit">Cadastrar</button>
            </div>
        </form>

    </div>
</div>


<script>
    function showModal(idModal) {
        var modal = document.getElementById(idModal);
        scrollTo(0, 0);
        modal.classList.remove("modal-information-invisible");
    }

    function closeModal(event, idModal) {
        var modal = document.getElementById(idModal);
        event.preventDefault();
        modal.classList.add("modal-information-invisible");
        var elements = document.querySelector('#'+idModal).querySelector('form').querySelectorAll('input[type=text] ,input[type=email]');
        elements.forEach((input)=>{
            input.value = "";
        });
    }

    function submitEvent(event, idModal,idForm) {
        //event.preventDefault();
        if (document.getElementById('idForm').checkValidity()) {
            var modal = document.getElementById(idModal);
            modal.classList.add("modal-information-invisible");
        }
       
        

    }
</script>
<!-- Styles -->
<div class='banner text-center d-flex align-items-center justify-content-center '>
    <h1 class='text-white'>Sobre & Colabore</h1>
</div>
@if($errors->any())
<div class="d-flex alert alert-danger justify-content-center"><p class="">{{$errors->first()}}</p></div>
@endif
<div class='container py-5' id="top-container">

    <div class='row'>
        <div class="col-md-5 p-text">

            <h2>O Que é o Portal das Disciplinas</h2>
            <div class="row justify-content">
                <div class="col-md-8">
                    <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px; margin-bottom: 8%">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE" allowfullscreen></iframe>
                    </div>
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
            <div class="info-collaborators-container">
                <h2 class="info-collaborators">
                    @if(Auth::user() && Auth::user()->isAdmin)
                        {{$sectionNameCurrentCollaborators ?? "[editar - Colaboradores Atuais]"}}
                    @elseif(isset($manager))
                        {{$sectionNameCurrentCollaborators ?? ""}}
                    @endif    
                </h2>
                @if(Auth::user() && Auth::user()->isAdmin)
                <span onclick="showModal('modal-texto-colaboradores1')">editar</span>
                @endif
            </div>
            <div class="row">
                @if(isset($manager))
                @component('components.info_contributors')
                @slot('name') {{$manager->name}} @endslot
                @slot('profession') {{$manager->bond}} @endslot
                @slot('occupation') {{$manager->role}} @endslot
                @slot('email') {{$manager->email}} @endslot
                @slot('lattes') {{$manager->lattes}} @endslot
                @slot('image') {{$manager->urlPhoto}} @endslot
                @slot('github') {{$manager->github}} @endslot
                @endcomponent
                @endif
            </div>
            @if(Auth::user() && Auth::user()->isAdmin)
            <button id="showb" class="btn btn-outline-success btn-sm" onclick="showModal('modal-information')">Adicionar Colaborador</button>
            @endif


            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4">
                    @component('components.info_contributors')
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') $collaborator->name @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                    @slot('idCollaborator') {{$collaborator->id}} @endslot
                    @endcomponent
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <form action="collaborators/{{$collaborator->id}}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-danger btn-sm align-text-bottom" value="remover">
                    </form>
                    @endif
                </div>

                @endforeach

            </div>

            <div class="info-collaborators-container">
                <h2 class="info-collaborators">
                    @if(Auth::user() && Auth::user()->isAdmin)
                        {{$sectionNameFormerCollaborators ?? "[editar - Antigos Colaboradores]"}}
                    @elseif(sizeof($formerCollaborators)>0)
                        {{$sectionNameFormerCollaborators ?? ""}}
                    @endif
                 </h2>
                @if(Auth::user() && Auth::user()->isAdmin)
                <span onclick="showModal('modal-texto-colaboradores2')">editar</span>
                @endif
            </div>
            @if(sizeof($formerCollaborators)>0)
            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($formerCollaborators as $collaborator)
                <div class="d-flex flex-column  align-items-center justify-content-between mt-4">
                    @component('components.info_contributors')
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') $collaborator->name @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                    @endcomponent
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <form action="collaborators/{{$collaborator->id}}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-danger btn-sm align-text-bottom" value="remover">
                    </form>
                    @endif
                </div>
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


@endsection