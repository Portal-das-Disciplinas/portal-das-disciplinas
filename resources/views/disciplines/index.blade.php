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
                <form id="filter" action="/discipline/filter" method="GET">
                    @csrf
                    <div class="input-group search-bar">
                        <input 
                            id="name_discipline"
                            type="text" 
                            class="form-control col-9" 
                            placeholder="Nome da disciplina" 
                            aria-label="Caixa de pesquisa" 
                            aria-describedby="button-addon2" 
                            name='name_discipline' 
                            value="{{ $name_discipline ?? '' }}" 
                        />
                        
                        <select name="emphasis" id="emphasis" class='form-control col-3' >
                            <option selected value=""> Todas as ênfases </option>
                            @foreach($emphasis ?? '' as $emphase)
                                <option value="{{ $emphase->id }}">{{ $emphase->name }}</option>
                            @endforeach
                        </select>

                        <div class="input-group-append">
                            <button id="pesquisar" class="btn btn-primary search-button" type="submit"><i class='fas fa-search search-icon'></i>Pesquisar</button>
                        </div>
                    </div>
            </div>
        </div>

        <div id="accordion">
            <div class="card">
                <div class="card-header row" id="headingOne">
                  <div class="col">
                    <h5 class="mb-0">
                        <button 
                            type="button" 
                            id="AccordionButton" 
                            class="btn btn-link" 
                            data-toggle="collapse" 
                            data-target="#collapseOne" 
                            aria-expanded="false" 
                            aria-controls="collapseOne"
                        >
                        Filtragem via Classificações
                        </button>
                    </h5>
                  </div>

                  <input type="reset" style="display:none" id="resetButton" value="">

                    <div class="col" style="display: flex; justify-content: center;">
                        <h5 class="mb-0">
                            <button 
                            
                                type="button" 
                                id="aprovationsButton" 
                                class="btn btn-link" 
                                data-toggle="collapse" 
                                data-target="#collapseTwo" 
                                aria-expanded="false" 
                                aria-controls="collapseTwo"
                            >
                            Filtragem via Aprovações
                            </button>
                        </h5>
                    </div>

                    <div class="col" style="display: flex; justify-content: flex-end;">
                        <h5 class="mb-0">
                            <button 
                                type="button" 
                                id="professorsButton" 
                                class="btn btn-link" 
                                data-toggle="collapse" 
                                data-target="#collapseThree" 
                                aria-expanded="false" 
                                aria-controls="collapseThree"
                            >
                            Filtragem via Professor
                            </button>
                        </h5>
                    </div>
                </div>

                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body" style="padding-left: 0; padding-right:0;">
                        <div class="container" style="padding-left: 0; padding-right:0;">
                            <div class="col" id="caracteristicas" style="display: flex; justify-content: center;">
                                <h3>Característica Predominante</h3>
                            </div>
                            <br>

                            <!-- input que indica qual o filtro que está sendo enviado -->
                            <div style="display:none">
                            <input type="text" name="filtro" id="filtro" value="">
                            </div>

                            @foreach($classifications as $classification)
                                <div class="row">
                                    <div class="col-4" id="classificationName" style="padding-right:0;">
                                        <a class="flex-sm-fill text-sm-center nav-link">{{ $classification->name }}</a>
                                    </div>
                                    <div class="col">
                                        <div class="simpleSearch row">
                                                <div class="col-6 nav-link">
                                                    <input 
                                                        type="radio" 
                                                        value="menos" 
                                                        name="{{ ($classification->name) }}"
                                                    >
                                                    <label for="inputA">{{ $classification->type_a }}</label>
                                                </div>
                                                <div class="col-6 nav-link">
                                                    <input  
                                                        type="radio" 
                                                        value="mais" 
                                                        name="{{ ($classification->name) }}"
                                                    >
                                                    <label for="inputB">{{ $classification->type_b }}</label>
                                                </div>
                                        </div>

                                        <div class="advancedSearch">
                                            <div class="col">
                                                <div class="value" style="justify-content: space-between">
                                                    <div style="margin-right: 20px;" id="{{ $classification->type_a }}">
                                                        <label id="{{ $classification->id }}" class="mostrador" style="color: var(--primary); font-weight: bold; display: block; font-size: 15px;">> 0%</label>
                                                        <label>{{ $classification->type_a }}</label>
                                                        <input type="checkbox" id="checkbox{{ $classification->type_a }}" onchange="toggleCheckboxes(this, '{{ $classification->type_b }}', 'range{{ $classification->id }}')">
                                                    </div>
                                                    <div  id="{{ $classification->type_b }}">
                                                        <label id="-{{ $classification->id }}" class="mostrador" style="color: var(--primary); font-weight: bold; display: block; font-size: 15px;">> 0%</label>
                                                        <label>{{ $classification->type_b }}</label>
                                                        <input type="checkbox" id="checkbox{{ $classification->type_b }}" onchange="toggleCheckboxes(this, '{{ $classification->type_a }}', 'range{{ $classification->id }}')">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <input class="range" type="range" style="width: 100%; display: none;" id="range{{ $classification->id }}" name="range{{ $classification->name }}" value="-1" min="-1" max="100" step="5" oninput="handleInput(this.id, this.value)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                            @endforeach
                            <div class="container">
                                <div class="row">
                                    <div class="col">
                                    </div>

                                    <div class="col">
                                        <button 
                                            id="advancedOptionButton" 
                                            style="float:right; padding: 15px; margin: 5px;" 
                                            type="button"
                                            class="btn btn-link" 
                                            data-toggle="tooltip" 
                                            data-placement="right" 
                                            title="Pesquisa Avançada"
                                        >
                                            Filtragem Avançada
                                        </button>

                                        <button  
                                            id="unmarkAll"
                                            style="float:right; padding: 15px; margin: 5px;" 
                                            type="button"
                                            class="btn btn-link" 
                                            data-toggle="tooltip" 
                                            data-placement="right" 
                                            title="Pesquisa Avançada"
                                        >
                                            Desmarcar tudo
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>  
                    </div>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card card-body">    
                    @if (count($studentsData) > 0)
                        <div class="card-body">
                            <div class="container">
                                <div class="col" style="display: flex; justify-content: center;">
                                    <h3>Escolha as opções de Filtragem</h3>
                                </div>
                                <br>
                                <div class="col" style="display: flex; justify-content: center;">
                                        <div>
                                        <select name="metodo" id="metodo">
                                            <option value="aprovacao">Aprovação</option>
                                            <option value="reprovacao">Reprovação</option>
                                        </select>
                                        <select name="maiorMenor" id="">
                                            <option value="maior">Maior que</option>
                                            <option value="menor">Menor que</option>
                                        </select>
                                        <input 
                                            type="number" 
                                            name="porcentagem" 
                                            id="porcentagem" 
                                            min="1" 
                                            max="100"
                                            value="null"
                                            style="width:20%;"
                                        />
                                        <label for="">%</label>
                                        </div>

                                        <br>
                                        <br>

                                        <div>
                                        <label for="periodo">Ano e período:</label>
                                        <select name="periodo" id="periodo">
                                            <option value="vazio" selected>Vazio</option>
                                            @foreach($periodsColection as $periods)
                                                <option value="{{ $periods }}" >{{ $periods }}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="response-search mt-4"> Nenhum dado encontrado </p>
                    @endif
                    </div>
                </div>

                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card card-body" style="display:flex; align-items:center;">  
                        <div class="col" style="display: flex; justify-content: center;">
                            <h3>Escolha o Professor</h3>
                        </div>

                        <br>

                        <select name="professors" id="professors" style="width:40%;">
                            <option value="null" name="professor" selected>Selecione</option>
                            @foreach($professors as $professor)
                                <option value="{{ $professor->id }}">{{ $professor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </form>
                <div id="autocomplete-results" class="autocomplete-results"></div>
            </div>
        </div>
        
    @isset($disciplines)
        @if(count($disciplines) == 0)
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
                                            src="{{$discipline->trailer->view_url}}" allowfullscreen></iframe>
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
    <div style="display:flex; justify-content:center;">
    @if (request()->name_discipline == null && request()->emphasis == null)
        {{ $disciplines->appends(["name_discipline" => "", "emphasis" => "", [$disciplines->withQueryString()]])->links() }}    
    @else
        {{ $disciplines->withQueryString()->links() }}
    @endif
    </div>
</section>
</div>
</div>

<script src="{{ asset('js/indexClassificationForm.js') }}"></script>
<script src="{{ asset('js/indexSlider.js') }}"></script>
<script src="{{ asset('js/disciplineAutoComplete.js') }}"></script>
@endsection
