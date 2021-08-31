@extends('layouts.app')

@section('title')
Sobre nós - Portal das Disciplinas IMD
@endsection

@section('content')

<div class='discipline-banner text-center d-flex align-items-center justify-content-center text-white'>
    <h1>Sobre & Colabore</h1>
</div>

<div class='mt-8 container text-white'  id="top-container">
    <div class='row'>
        <div class="col-md-5">
                <div>
                    <h2>Nossa equipe</h2>
                    <p class="text-justify">Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestias eveniet temporibus dolorum facere ullam repellat doloribus architecto tempore unde quae? Tempore cumque dolor velit enim aliquam laboriosam, dignissimos doloremque odio? Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                </div>

                <div>
                    <h2>Colabore</h2>
                    <p class="text-justify">Caso tenha interesse em colaborar na adição de novas funcionalidades do site como sistema de classificações dinâmicos, refinamento de mecanismos de busca, interação com o portal de dados abertos da UFRN para recuperação de índices de aprovação de disciplinas, implementação de fóruns no portal, entre outros, por favor, entre em contato conosco.</p>
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
                <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">Eugênio Paccelli</h1>
                        <p>Professor Orientador</p>
                    </div>
            </div>
            

            <div class="d-flex justify-content-center align-items-center mx-4">

                <div class="col-md-6">
                    <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">IMD Dev</h1>
                        <p>Desenvolvedor <code>back-end</code></p>
                    </div>
                
                <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">IMD Dev</h1>
                        <p>Desenvolvedor <code>back-end</code></p>
                    </div>

                <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">IMD Dev</h1>
                        <p>Desenvolvedor <code>back-end</code></p>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">IMD Dev</h1>
                        <p>Desenvolvedor <code>back-end</code></p>
                    </div>
                
                <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">IMD Dev</h1>
                        <p>Desenvolvedor <code>back-end</code></p>
                    </div>

                <img class="fluid clip-path" src="" alt="foto-dev">
                    <div class="text-center">
                        <h1 style="font-size: 1.4rem" class="mb-0">IMD Dev</h1>
                        <p>Desenvolvedor <code>back-end</code></p>
                    </div>
                </div>     

        </div>

        </div>
        
        


        
    </div>
</div>
@endsection