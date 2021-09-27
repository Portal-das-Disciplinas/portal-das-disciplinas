@extends('layouts.app')

@section('title')
Sobre nós - Portal das Disciplinas IMD
@endsection

@section('content')

<div class='discipline-banner text-center d-flex align-items-center justify-content-center '>
    <h1 class='text-white'>Sobre & Colabore</h1>
</div>

<div class='mt-6 container mb-5'  id="top-container" >
    <div class='row'>
        <div class="col-md-5 p-text">
                <div>
                    <h2>Nossa equipe</h2>
                    <p class="text-justify mb-3">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias eveniet temporibus dolorum facere ullam repellat doloribus architecto tempore unde quae? Tempore cumque dolor velit enim aliquam laboriosam, dignissimos doloremque odio? Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                </div>

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
                    @slot('name', 'Docente')
                    @slot('profession', 'Docente')
                    @slot('occupation', 'disciplina')
                @endcomponent
            </div>
            
            <div class="d-flex flex-wrap justify-content-around mt-4">
                @component('components.info_contributors')
                    @slot('name', 'Henry')
                    @slot('profession', 'Desenvolvedor')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'front-end')
                    @slot('email', 'henrymedeiros77@gmail.com')
                    @slot('lattes', 'http://lattes.cnpq.br/9829493020424534')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'Pedro')
                    @slot('profession', 'Desenvolvedor')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'IMD Dev')
                    @slot('profession', 'Desenvolvedor')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                @endcomponent

                @component('components.info_contributors')
                    @slot('name', 'IMD Dev')
                    @slot('profession', 'Desenvolvedor')
                    @slot('alt_image', 'foto-dev')
                    @slot('occupation', 'back-end')
                @endcomponent

            </div>
        </div>   
    </div>

    <h4 class='mb-3'>Colaboradores Anteriores</h4>
    <hr>
    <div class="row ">
        
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
        </div>
    </div>
   
    
    
        
        


        
    
</div>
@endsection