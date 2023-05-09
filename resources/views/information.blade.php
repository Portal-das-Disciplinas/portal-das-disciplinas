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
        <form action="{{route('collaborators.store')}}"enctype="multipart/form-data" method='post'>
            @csrf
            <label for="fotoColaborador" name="foto">Foto</label>
            <input id="fotoColaborador" name="foto" type='file'>
            <label for="nomeColaborador">Nome</label>
            <input id="nomeColaborador" name="nome" type=text placeholder="Nome e Sobrenome">
            <label for="emailColaborador">e-mail</label>
            <input id="emailColaborador" name="email" type="email" placeholder="E-mail">
            <label for="vinculoColaborador">Vínculo</label>
            <input id="vinculoColaborador" name="vinculo" type="text" placeholder="bolsista, voluntário...">
            <label for="funcaoColaborador">Função</label>
            <input id="funcaoColaborador" name="funcao" type=text placeholder="Desenvolvedor, Designer, ...">
            <label for="lattesColaborador">Lattes</label>
            <input id="lattesColaborador" name="lattes" type="text" placeholder="Endereço do currículo latttes">
            <label for="githubColaborador">Github</label>
            <input id="githubColaborador" name="github" type="text" placeholder="Github">
            <div>
                <label for="colaboradorAtivo">Ativo</label>
                <input id="colaboradorAtivo" name="ativo" type="checkbox" checked>
            </div>
            <div>
                <label for="coordenador">Coordenador</label>
                <input id="coordenador" name="coordenador" type="checkbox">
            </div>
            <div class="buttons">
                <button id="btn-fechar" class=" btn btn-info">Fechar</button class="btn">
                <button id="btn-cadastrar" class="btn btn-success" type="submit">Cadastrar</button>
            </div>
        </form>
    </div>

</div>
<script>
    var modal = document.getElementById("modal-information");
    function showModal() {
        scrollTo(0,0);
        modal.classList.remove("modal-information-invisible");
    }

    document.getElementById("btn-fechar").addEventListener('click',function(event){
        event.preventDefault();
        modal.classList.add("modal-information-invisible");
    });

    document.getElementById("btn-cadastrar").addEventListener('click',function(event){
        modal.classList.add("modal-information-invisible");
    });
</script>
<!-- Styles -->
<div class='banner text-center d-flex align-items-center justify-content-center '>
    <h1 class='text-white'>Sobre & Colabore</h1>
</div>

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
            <h1 class="info-collaborators">{{$sectionNameCurrentCollaborators ?? "Colaboradores Atuais"}}</h1>
            <div class="row">
                @component('components.info_contributors')
                @slot('name') {{$manager->name}} @endslot
                @slot('profession') {{$manager->bond}} @endslot
                @slot('occupation') {{$manager->role}} @endslot
                @slot('email') {{$manager->email}} @endslot
                @slot('lattes') {{$manager->lattes}} @endslot
                @slot('image') {{$manager->urlPhoto}} @endslot
                @slot('github') {{$manager->github}} @endslot
                @endcomponent
            </div>
            @if(Auth::user() && Auth::user()->isAdmin)
            <button id="showb" class="btn btn-success"onclick="showModal()">Adicionar Colaborador</button>
            @endif


            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($collaborators as $collaborator)
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
                @endforeach

            </div>

            @if(sizeof($formerCollaborators)>0)
            <h1 class="info-collaborators">{{$sectionNameFormerCollaborators ?? "Antigos Colaboradores"}} </h1>
            <div class="d-flex flex-wrap justify-content-around mt-4">
                @foreach($formerCollaborators as $collaborator)
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