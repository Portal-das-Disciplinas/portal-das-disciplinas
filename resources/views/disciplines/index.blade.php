@extends('layouts.app')
@section('styles-head')
<link rel="stylesheet" href="{{asset('css/index.css')}}">
@endsection
@section('content')


    <div class="row">
        <div class="col-12 text-center my-4 title-subject-container">
            <h1 class="title-subject display-title " style='color: #1F2937'>Portal das Disciplinas - IMD/UFRN</h1>
            <div class="row justify-content-center">
                <p class='p-text mt-3  text-center col-md-10 '>Cada disciplina aqui conta com entrevistas, informações, materiais, indicação de dificuldades e muito mais sobre esses componentes curriculares do Bacharelado em TI do IMD/UFRN.<p>

<section class='hero-section'>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center my-4 title-subject-container">
                <h1 class="title-subject display-title " style='color: #1F2937'>Portal das Disciplinas - IMD/UFRN</h1>
                <div class="row justify-content-center">
                    <p class='p-text mt-3  text-center col-md-10  larger-p'>Cada disciplina aqui conta com entrevistas,
                        informações, materiais, indicação de dificuldades e muito mais sobre esses componentes
                        curriculares do Bacharelado em TI do IMD/UFRN.<p>
                </div>

            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px">
                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE"
                        allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </div>

</section>

<section class='disciplines remove-margin-bottom'>
    <div class="disciplines-container container">
        <h1 class='text-center text-white'>Disciplinas Cadastradas</h1>
        <p class='text-center p-text' style='color: #80A6C6'>Lista de disciplinas disponíveis no portal... sempre
            crescendo!
        </p>
        @auth
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-3 mt-5">
                    <a name="createDisciplina" class="create-discipline btn btn-outline-light btn-block"
                        href="{{ route("disciplinas.create") }}" role="button">Cadastrar
                        disciplina</a>
                </div>
            </div>
        @endauth

          <div class="row justify-content-md-center mt-5">
            <div class="col">
            <form action="{{route('index')}}" method="GET">
                @csrf
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nome da Disciplina..." aria-label="Caixa de pesquisa" aria-describedby="button-addon2" name='search' value="{{ $search ?? '' }}">

                            <select name="emphasis" id="emphasis" class='form-control' >
                                <option selected disabled > Selecione uma ênfase</option>
                                <option value="Computação">Computação</option>
                                <option value="Desenvolvimento de Software">Desenvolvimento de Software</option>
                                <option value="Informática Educacional">Informática Educacional</option>
                                <option value="Sistemas de Informação de Gestão">Sistemas de Informação de Gestão</option>
                                <option value="Bioinformática">Bioinformática</option>
                                <option value="Internet das Coisas">Internet das Coisas</option>
                                <option value="Jogos Digitais">Jogos Digitais</option>
                            </select>

                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="button-addon2">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>

        </div> --}}
    
        @isset($disciplines)
            @if ($disciplines->count() == 0)
                <p class="response-search mt-4"> Nenhuma disciplina encontrada </p>
            @else
                <div class="row pb-5" >
                    @foreach ($disciplines as $discipline)
                        <div class="col-12 col-sm-6 col-lg-3 mt-5">
                            <div class="card shadow light-border-radius card-discipline" style="min-height: 400px; max-height: 500px; max-width: 100%" >
                                @if (!is_null($discipline->trailer))
                                    <div class="teacher-video-container">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="{{ $discipline->trailer->view_url }}" allowfullscreen></iframe>
                                        </div>

        </div>

    @isset($disciplines)
        @if($disciplines->count() == 0)
            <p class="response-search mt-4"> Nenhuma disciplina encontrada </p>
        @else
            <div class="row pb-5">
                @foreach($disciplines as $discipline)
                    <div class="col-12 col-sm-6 col-lg-3 mt-5 ">
                        <div class="discipline-card card shadow light-border-radius">
                            @if(!is_null($discipline->trailer))
                                <div class="teacher-video-container">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item light-border-radius"
                                            src="{{ $discipline->trailer->view_url }}" allowfullscreen></iframe>

                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('img/IMD_logo.svg') }}" class="card-img-top light-border-radius"
                                    alt=".."'>
                            @endif

                            <div class="card-body d-flex justify-content-between flex-column">
                                <div class="card-top-container">
                                    <h3 class="card-title">{{ $discipline->name }}</h3>
                                    <p class='card-text p-text'>
                                        {{ Str::limit($discipline->synopsis, 70,' (...)') }}
                                    </p>

                                </div>
                                <div class="card-bottom-container">
                                    <a href="{{ route('disciplinas.show', $discipline->id) }}"
                                        class="view-more-btn btn w-100 p-text">Ver
                                        mais</a>
                                    @auth
                                        <div class='d-flex justify-content-end'>
                                            @if(Auth::user()->canDiscipline($discipline->id))
                                                <div class="dropdown show">
                                                    <div class="advanced-options d-flex align-items-center mt-2 p-text"
                                                        data-toggle="dropdown">
                                                        <a class='mr-2'>Opções avançadas</a>
                                                        <i class="fas fa-caret-down"></i>
                                                    </div>
                                                    <div class="user-dropdown dropdown-menu">

                                                        <form
                                                            action=" {{ route('disciplinas.destroy', $discipline->id) }}"
                                                            class="dropdown-item" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger w-100 p-text "
                                                                value="Apagar">Apagar</button>
                                                        </form>
                                                        <form
                                                            action=" {{ route('disciplinas.edit', $discipline->id) }}"
                                                            class="dropdown-item" method="get" class='dropdown-item'>
                                                            @csrf
                                                            @method('UPDATE')
                                                            <button type="submit" class="btn btn-warning w-100 p-text m-0"
                                                                value="Editar">Editar</button>
                                                        </form>

                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                    @endauth
                                </div>




                            </div>
                            <div class="card-footer smaller-p m-0">{{ $discipline->professor->name }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endisset

    </div>

</section>
</div>
</div>

<style scoped>

/* Media Queries */

/* @media screen and (max-width:770px){
    .display-title{
        font-size:3.5rem;
    }

    
} */

@media screen and (max-width:576px){
    .container{
        justify-content: center;
    }
}

@media screen and (max-width:446px){
    #imd-footer{
        display: none;
    }
    #pdd-title{
        width: 100%;
        margin: 0 !important;
        text-align: center !important;
    }
}

</style>
@endsection
