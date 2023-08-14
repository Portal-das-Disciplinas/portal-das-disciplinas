@extends('layouts.app')
@section('styles-head')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')
<section class='hero-section mb-4'>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center my-4 title-subject-container">
                <h1 class="title-subject display-title ">Portal das Disciplinas - {{$theme['PROJETO_SIGLA_SETOR_INSTITUICAO']}}</h1>
                <div class="row justify-content-center">
                    <p class='p-text mt-3  text-center col-md-10  larger-p'>{{$theme['PROJETO_DISCIPLINAS_DESCRICAO']}}<p>
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
                        href="{{ route('disciplinas.create') }}" role="button">Cadastrar
                        disciplina</a>
                </div>
            </div>
        @endauth
        
        <div class="row justify-content-md-center mt-5" style="margin-bottom:25px;">
            <div class="col">
                <form action="/discipline/filter" method="GET">
                    @csrf
                    <div class="input-group search-bar">
                        <input type="text" class="form-control col-9" placeholder="Nome da disciplina" aria-label="Caixa de pesquisa" 
                        aria-describedby="button-addon2" name='name_discipline' value="{{ $name_discipline ?? '' }}" />
                        
                        <select name="emphasis" id="emphasis" class='form-control col-3' >
                            <option selected value=""> Todas as ênfases </option>
                            @foreach($emphasis ?? '' as $emphase)
                                <option value="{{ $emphase->id }}">{{ $emphase->name }}</option>
                            @endforeach
                        </select>

                        <div class="input-group-append">
                            <button class="btn btn-primary search-button" type="submit" id="button-addon2"><i class='fas fa-search search-icon'></i>Pesquisar</button>
                        </div>
                    </div>
                </form>
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
                            @if(!is_null($discipline['trailer']))
                                <div class="teacher-video-container">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item light-border-radius"
                                            src="{{ $discipline['trailer']['url'] }}" allowfullscreen></iframe>
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('img/IMD_logo.svg') }}" class="card-img-top light-border-radius"
                                    alt="..">
                            @endif

                            <div class="card-body d-flex justify-content-between flex-column">
                                <div class="card-top-container">
                                    <h3 class="card-title">{{ $discipline['name'] }}</h3>
                                    <p class='card-text p-text'>
                                        {{ Str::limit($discipline['description'], 70,' (...)') }}
                                    </p>

                                </div>
                                <div class="card-bottom-container">
                                    <a href="{{ route('disciplinas.show', $discipline['id']) }}"
                                        class="view-more-btn btn w-100 p-text">Ver
                                        disciplina</a>
                                    @auth
                                        <div class='d-flex justify-content-end'>
                                            @if(Auth::user()->canDiscipline($discipline['id']))
                                                <div class="dropdown show">
                                                    <div class="advanced-options d-flex align-items-center mt-2 p-text"
                                                        data-toggle="dropdown">
                                                        <a class='mr-2' style="cursor:pointer">Opções avançadas</a>
                                                        <i class="fas fa-caret-down"></i>
                                                    </div>
                                                    <div class="user-dropdown dropdown-menu">

                                                        <form
                                                            action=" {{ route('disciplinas.destroy', $discipline['id']) }}"
                                                            class="dropdown-item" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger w-100 p-text "
                                                                value="Apagar">Apagar</button>
                                                        </form>
                                                        <form
                                                            action=" {{ route('disciplinas.edit', $discipline['id']) }}"
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
                            @if (isset($discipline->professor->name))
                            <div class="card-footer smaller-p m-0">{{$discipline->professor->name}}</div>
                            @else
                            <div class="card-footer smaller-p m-0">Indefinido</div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endisset
    </div>
    {{ $disciplines->appends(request()->input())->links('layouts.paginationLinks') }}
</section>
</div>
</div>

<script src="{{ asset('js/indexSidebar.js') }}"></script>
@endsection
