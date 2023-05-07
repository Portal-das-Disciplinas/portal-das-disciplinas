@extends('layouts.app')

@section('title')
Sobre nós - Portal das Disciplinas IMD
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/about.css')}}">
@endsection

@section('content')
<!-- Styles -->
<div class='banner text-center d-flex align-items-center justify-content-center '>
    <h1 class='text-white'>Sobre & Colabore</h1>
</div>

<div class='container py-5'  id="top-container" >
    <div class='row'>
        <div class="col-md-5 p-text">
            
        <h2>O Que é o Portal das Disciplinas</h2>
        <div class="row justify-content">
            <div class="col-md-8">
                <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px; margin-bottom: 8%">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE"
                        allowfullscreen></iframe>
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
        <h1 class="info-collaborators">Colaboradores atuais</h1>
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
                        
            <div class="d-flex flex-wrap justify-content-around mt-4">
            @foreach($collaborators as $collaborator)
                @component('components.info_contributors')
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') $collaborator->name  @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                @endcomponent
            @endforeach

            </div>
            <h1 class="info-collaborators">Antigos colaboradores </h1>
            <div class="d-flex flex-wrap justify-content-around mt-4">
            @foreach($formerCollaborators as $collaborator)
                @component('components.info_contributors')
                    @slot('name') {{$collaborator->name}} @endslot
                    @slot('profession') {{$collaborator->role}} @endslot
                    @slot('occupation') {{$collaborator->bond}} @endslot
                    @slot('image') {{$collaborator->urlPhoto}} @endslot
                    @slot('alt_image') $collaborator->name  @endslot
                    @slot('email'){{$collaborator->email}} @endslot
                    @slot('lattes') {{$collaborator->lattes}} @endslot
                    @slot('github') {{$collaborator->github}} @endslot
                @endcomponent
            @endforeach
            </div>
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
