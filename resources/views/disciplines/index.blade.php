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

        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                  <h5 class="mb-0">
                    <button id="filterButton" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Filtragem via Classificações
                    </button>

                    <button id="advancedFilter" style="float:right;" type="button" data-toggle="tooltip" data-placement="right" title="Pesquisa Avançada">
                        <svg id="i-edit" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                            <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                        </svg>
                    </button>
                  </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form action="/discipline/filter/advanced" method="get">
                            <div class="container">
                                <div>
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col" style="display: flex; justify-content:center;">
                                            <button type="button">
                                                <input type="checkbox" name="triggerMetodologia" id="triggerMetodologia" />
                                                <label for="triggerMetodologia"> Metodologias </label>
                                            </button>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="row" id="metodologias" style="display:none;">
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                            <input type="radio" value="classicas" name="metodologias" id="classicas">
                                            <label for="classicas">Clássicas</label>
                                        </button>
                                      </div>
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                            <input type="radio" value="ativas" name="metodologias" id="ativas">
                                            <label for="ativas">Ativas</label> 
                                        </button>
                                      </div>
                                    </div>
                                    <div id="metodologias-range" style="display:none;">
                                        Clássicas <input value="-1" type="range" name="metodologias_range" class="form-control-range" id="range-metodologia" min="-1" max="100"> Ativas
                                    </div>
                                </div>

                                <div>
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col" style="display: flex; justify-content:center;">
                                            <button type="button">
                                            <input type="checkbox" name="triggerDiscussao" id="triggerDiscussao">
                                            <label for="triggerDiscussao">Discussão</label>
                                            </button>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="row" id="discussao" style="display:none;">
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                            <input type="radio" value="social" name="discussao" id="social">
                                            <label for="social">Social</label>
                                        </button>
                                      </div>
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                        <input type="radio" value="tecnica" name="discussao" id="tecnica">
                                        <label for="tecnica">Técnica</label>
                                        </button>
                                      </div>
                                    </div>
                                    <div id="discussao-range" style="display:none;">
                                        Social <input value="-1" type="range" name="discussao_range" class="form-control-range" id="discussao_range" min="-1" max="100">Técnica
                                    </div>
                                </div>

                                <div>
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col" style="display: flex; justify-content:center;">
                                            <button type="button">
                                            <input type="checkbox" name="triggerAbordagem" id="triggerAbordagem">
                                            <label for="triggerAbordagem">Abordagem</label>
                                            </button>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="row" id="abordagem" style="display:none;">
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                        <input type="radio" value="teorica" name="abordagem" id="teorica">
                                        <label for="teorica">Teórica</label>                                        
                                        </button>
                                      </div>
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                        <input type="radio" value="pratica" name="abordagem" id="pratica">
                                        <label for="pratica">Prática</label>
                                        </button>
                                      </div>
                                    </div>
                                    <div id="abordagem-range" style="display:none;">
                                        Teórica <input value="-1" type="range" name="abordagem_range" class="form-control-range" id="abordagem_range" min="-1" max="100">Prática
                                    </div>
                                </div>

                                <div>
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col" style="display: flex; justify-content:center;">
                                            <button type="button">
                                            <input type="checkbox" name="triggerAvaliacao" id="triggerAvaliacao">
                                            <label for="triggerAvaliacao">Avaliação</label>
                                            </button>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="row" id="avaliacao" style="display:none;">
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                        <input type="radio" value="provas" name="avaliacao" id="provas">
                                        <label for="provas">Provas</label> 
                                        </button>
                                      </div>
                                      <div class="col" style="display: flex; justify-content:center;">
                                        <button type="button">
                                        <input type="radio" value="atividades" name="avaliacao" id="atividades">
                                        <label for="atividades">Atividades</label>
                                        </button>
                                      </div>
                                    </div>
                                    <div id="avaliacao-range" style="display:none;">
                                        Provas <input value="-1" type="range" name="avaliacao_range" class="form-control-range" id="avaliacao_range" min="-1" max="100"> Atividades
                                    </div>
                                </div>
                                <button type="submit">FILTRAR</button>
                            </div>
                        </form>  
                    </div>
                </div>
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
                                    alt="..">
                            @endif

                            <div class="card-body d-flex justify-content-between flex-column">
                                <div class="card-top-container">
                                    <h3 class="card-title">{{ $discipline->name }}</h3>
                                    <p class='card-text p-text'>
                                        {{ Str::limit($discipline->description, 70,' (...)') }}
                                    </p>

                                </div>
                                <div class="card-bottom-container">
                                    <a href="{{ route('disciplinas.show', $discipline->id) }}"
                                        class="view-more-btn btn w-100 p-text">Ver
                                        disciplina</a>
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
    <ul class="nav justify-content-center">
          <li class="nav-item" style="background-color:#fff">
            <a class="nav-link active" href="#">Active</a>
          </li>
          <li class="nav-item" style="background-color:#fff">
            <a class="nav-link active" href="#">Link</a>
          </li>
        </ul>
    </div>
</section>
</div>
</div>

<script src="{{ asset('js/indexSidebar.js') }}"></script>
@endsection
