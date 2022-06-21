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
            <div class="row">
                @component('components.info_contributors')
                    @slot('name', 'Eugênio Paccelli')
                    @slot('profession', 'Docente')
                    @slot('occupation', 'Coordenador')
                    @slot('email', 'eugenio@imd.ufrn.br')
                    @slot('lattes', 'http://lattes.cnpq.br/6494297323272628')
                    @slot('image', 'img/eugenioq.jpeg')
                @endcomponent
            </div>

            <div class="d-flex flex-wrap justify-content-around mt-4">
                @component('components.info_contributors')
                    @slot('name', 'Henry Medeiros')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'img/henryq.jpg')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'front-end')
                    @slot('email', 'henrymedeiros77@gmail.com')
                    @slot('lattes', 'http://lattes.cnpq.br/9829493020424534')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Jefferson Felipe')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'https://avatars.githubusercontent.com/u/57409786?s=400&u=e9077490f96f76794bd1bf16a65c0e66858a4344&v=4')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                    @slot('email', 'jeff.felip@outlook.com')
                    @slot('lattes', 'http://lattes.cnpq.br/????')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Pedro Gabriel')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'img/pedro.jpeg')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                    @slot('email', 'pedrogab96@gmail.com')
                    @slot('lattes', 'http://lattes.cnpq.br/8217345027440939')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Cristian Soares')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'img/cristianq.jpg')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'front-end')
                    @slot('email', 'criricyt@gmail.com')
                    @slot('lattes', 'http://lattes.cnpq.br/1636913073567133')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Álvaro Ferreira')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'img/alvaro.jpeg')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                    @slot('email', 'alvarofepipa@gmail.com')
                    @slot('lattes', 'http://lattes.cnpq.br/2537818674954146')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Victor Brandão')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'img/victor.jpeg')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                    @slot('email', 'victor_brandao@outlook.com')
                    @slot('lattes', 'http://lattes.cnpq.br/5872826755197239')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Arthur Sérvulo')
                    @slot('profession', 'Desenvolvedor')
                    @slot('image', 'img/arthur.jpeg')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                    @slot('email', 'arthurservulo7@gmail.com')
                    @slot('lattes', 'http://lattes.cnpq.br/8112883352153781')
                @endcomponent



            </div>
        </div>
    </div>


    <h4 class='mb-3' hidden>Colaboradores Anteriores</h4>
    <hr>
    <div class="row " hidden>
        
        <div class="col-md-3 d-flex flex-column align-items-center">
            
            <img class="fluid clip-path" style="width:4rem" src="img/user2.png" alt="foto-dev">
            <p><strong> Nome do colaborador </strong></p>
            <p> Descrição </p>
            <p> 12/12/2021 - 12/12/2021</p>
        </div>
        <div class="col-md-3 d-flex flex-column align-items-center">
            
            <img class="fluid clip-path" style="width:4rem" src="img/user2.png" alt="foto-dev">
            <p><strong> Nome do colaborador </strong></p>
            <p> Descrição </p>
            <p> 12/12/2021 - 12/12/2021</p>
        </div>
        <div class="col-md-3 d-flex flex-column align-items-center">
            
            <img class="fluid clip-path" style="width:4rem" src="img/user2.png" alt="foto-dev">
            <p><strong> Nome do colaborador </strong></p>
            <p> Descrição </p>
            <p> 12/12/2021 - 12/12/2021</p>
        </div>
        <div class="col-md-3 d-flex flex-column align-items-center">
            
            <img class="fluid clip-path" style="width:4rem" src="img/user2.png" alt="foto-dev">
            <p><strong> Nome do colaborador </strong></p>
            <p> Descrição </p>
            <p> 12/12/2021 - 12/12/2021</p>

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
