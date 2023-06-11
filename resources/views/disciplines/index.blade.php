@extends('layouts.app')
@section('styles-head')
<link rel="stylesheet" href="{{asset('css/index.css')}}">
@endsection
@section('content')

<section class='hero-section mb-4'>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center my-4 title-subject-container">
                <h1 class="title-subject display-title " style='color: #1F2937'>Portal das Disciplinas - IMD/UFRN</h1>
                <div class="row justify-content-center">
                    <p class='p-text mt-3  text-center col-md-10  larger-p'>Cada disciplina aqui conta com entrevistas,
                        informações, materiais, indicação de dificuldades e muito mais sobre componentes
                        curriculares do Bacharelado em TI do IMD/UFRN.<p>
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
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                      Filtragem via Classificações
                    </button>
                  </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <form action="/discipline/filter/advanced" method="get">
                            <div class="container">
                              <div class="row">
                                <div class="col align-self-start">
                                <input type="checkbox" name="triggerMetodologia" id="triggerMetodologia"> Metodologias
                                </div>
                                <div class="col align-self-center">
                                    <div class="row" id="metodologias" style="display:none;">
                                      <div class="col">
                                      <button type="button"><input type="radio" value="classicas" name="metodologias" id=""> Clássicas</button>
                                      </div>
                                      <div class="col">
                                       <button type="button"><input type="radio" value="ativas" name="metodologias" id=""> Ativas</button>
                                      </div>
                                    </div>
                                    <div id="metodologias-range" style="display:none;">
                                        Clássicas<input value="-1" type="range" name="metodologias_range" class="form-control-range" id="range-metodologia" min="-1" max="100">Ativas
                                    </div>
                                </div>
                                <div class="col align-self-end" style="display:flex; justify-content:center;">
                                </div>
                              </div>

                              <div class="row">
                                <div class="col align-self-start">
                                <input type="checkbox" name="triggerDiscussao" id="triggerDiscussao"> Discussão
                                </div>
                                <div class="col align-self-center">
                                    <div class="row" id="discussao" style="display:none;">
                                      <div class="col">
                                        <button type="button"><input type="radio" value="social" name="discussao" id="discussao"> Social</button>
                                      </div>
                                      <div class="col">
                                        <button type="button"><input type="radio" value="tecnica" name="discussao" id="discussao"> Técnica</button>
                                      </div>
                                    </div>
                                    <div id="discussao-range" style="display:none">
                                        Social<input type="range" value="-1" class="form-control-range" name="discussao_range" id="range-discussao" min="-1" max="100">Técnica
                                    </div>
                                </div>
                                <div class="col align-self-end" style="display:flex; justify-content:center;">
                                    
                                </div>
                              </div>

                              <div class="row">
                                <div class="col align-self-start">
                                <input type="checkbox" name="triggerAbordagem" id="triggerAbordagem"> Abordagem
                                </div>
                                <div class="col align-self-center">
                                <div class="row" id="abordagem" style="display:none;">
                                    <div class="col">
                                        <button type="button"><input type="radio" value="teorica" name="abordagem" id="abordagem"> Teórica</button>
                                      </div>
                                      <div class="col">
                                        <button type="button"><input type="radio" value="pratica" name="abordagem" id="abordagem"> Prática</button>
                                      </div>
                                    </div>
                                    <div id="abordagem-range" style="display:none;">
                                        Teórica<input type="range" class="form-control-range" name="abordagem_range" value="-1" id="range-abordagem" min="-1" max="100">Prática
                                    </div>
                                </div>
                                <div class="col align-self-end" style="display:flex; justify-content:center;">
                                    <button type="button" id="abordagemButton" data-toggle="tooltip" data-placement="right" title="Pesquisa Avançada">
                                        <svg id="i-edit" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                                            <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                                        </svg>
                                    </button>
                                </div>
                              </div>

                              <div class="row">
                                <div class="col align-self-start">
                                <input type="checkbox" name="triggerAvaliacao" id="triggerAvaliacao"> Avaliação
                                </div>
                                <div class="col align-self-center">
                                <div class="row" id="avaliacao" style="display:none;">
                                    <div class="col">
                                        <button type="button"><input type="radio" value="provas" name="avaliacao" id="avaliacao"> Provas</button>
                                      </div>
                                      <div class="col">
                                        <button type="button"><input type="radio" value="atividades" name="avaliacao" id="avaliacao"> Atividades</button>
                                      </div>
                                    </div>
                                    <div style="display:none;" id="avaliacao-range">
                                        Provas<input type="range" class="form-control-range" name="avaliacao_range" value="-1" id="range-avaliacao" min="-1" max="100">Atividades
                                    </div>
                                </div>
                                <div class="col align-self-end" style="display:flex; justify-content:center;">
                                </div>
                              </div>

                              <div class="row">
                                <div class="col align-self-start">
                                  <input type="checkbox" name="triggerHorario" id="triggerHorario"> Carga Horária
                                </div>
                                <div class="col align-self-center">
                                <div class="row" id="horario" style="display:none;">
                                    <div class="col">
                                        <button type="button"><input type="radio" value="presencial" name="horario" id="horario"> Presencial</button>
                                      </div>
                                      <div class="col">
                                        <button type="button"><input type="radio" value="ead" name="horario" id="horario"> EAD</button>
                                      </div>
                                    </div>
                                    <div style="display:none;" id="horario-range">
                                        Presencial<input type="range" class="form-control-range" name="horario_range" value="-1" id="range-horario" min="-1" max="100">EAD
                                    </div>
                                </div>
                                <div class="col align-self-end" style="display:flex; justify-content:center;">
                                </div>
                                @foreach($disciplines as $discipline)
                                    @php
                                        $arr[] = $discipline->id;
                                    @endphp
                                @endforeach
                                <input type="text" name="currentDisciplines[]" value="{{ implode(',', $arr) }}" style="display:none;"/>
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

    </div>

</section>
</div>
</div>

<script>
    $("#triggerMetodologia").on("click", () => {
        if($("#metodologias").css("display") != "none"){
            $("#metodologias").css("display","none");
        } else {
            $("#metodologias").css("display","flex");
        }
    })
    
    $("#triggerDiscussao").on("click", () => {
        if($("#discussao").css("display") != "none"){
            $("#discussao").css("display","none");
        } else {
            $("#discussao").css("display","flex");
        }
    })

    $("#triggerAbordagem").on("click", () => {
        if($("#abordagem").css("display") != "none"){
            $("#abordagem").css("display","none");
        } else {
            $("#abordagem").css("display","flex");
        }
    })

    $("#triggerAvaliacao").on("click", () => {
        if($("#avaliacao").css("display") != "none"){
            $("#avaliacao").css("display","none");
        } else {
            $("#avaliacao").css("display","flex");
        }
    })

    $("#triggerHorario").on("click", () => {
        if($("#horario").css("display") != "none"){
            $("#horario").css("display","none");
        } else {
            $("#horario").css("display","flex");
        }
    })

    $("#abordagemButton").on("click", () => {
        $("#metodologias").css("display","none");
        $("#triggerMetodologia").prop('disabled', true);
        
        $("#range-metodologia").attr({'min': 0});
        $("#range-metodologia").attr({'value': 0});

        $("#discussao").css("display","none");
        $("#triggerDiscussao").prop('disabled', true);

        $("#range-discussao").attr({'min': 0});
        $("#range-discussao").attr({'value': 0});

        $("#abordagem").css("display","none");
        $("#triggerAbordagem").prop('disabled', true);

        $("#range-abordagem").attr({'min': 0});
        $("#range-abordagem").attr({'value': 0});

        $("#avaliacao").css("display","none");
        $("#triggerAvaliacao").prop('disabled', true);

        $("#range-avaliacao").attr({'min': 0});
        $("#range-avaliacao").attr({'value': 0});

        $("#horario").css("display","none");
        $("#triggerHorario").prop('disabled', true);

        $("#range-horario").attr({'min': 0});
        $("#range-horario").attr({'value': 0});

        if($("#abordagem-range").css("display") != "none"){
            $("#metodologias-range").css("display","none");
            $("#metodologias-range").val('');
            $("#triggerMetodologia").prop('checked', false);
            $("#triggerMetodologia").prop('disabled', false);

            $("#range-metodologia").attr({'min': -1});
            $("#range-metodologia").attr({'value': -1});

            $("#discussao-range").css("display","none");
            $("#discussao-range").val('');
            $("#triggerDiscussao").prop('checked', false);
            $("#triggerDiscussao").prop('disabled', false);

            $("#range-discussao").attr({'min': -1});
            $("#range-discussao").attr({'value': -1});

            $("#abordagem-range").css("display","none");
            $("#abordagem-range").val('');
            $("#triggerAbordagem").prop('checked', false);
            $("#triggerAbordagem").prop('disabled', false);

            $("#range-abordagem").attr({'min': -1});
            $("#range-abordagem").attr({'value': -1});

            $("#avaliacao-range").css("display","none");
            $("#avaliacao-range").val('');
            $("#triggerAvaliacao").prop('checked', false);
            $("#triggerAvaliacao").prop('disabled', false);

            $("#range-avaliacao").attr({'min': -1});
            $("#range-avaliacao").attr({'value': -1});

            $("#horario-range").css("display","none");
            $("#horario-range").val('');
            $("#triggerHorario").prop('checked', false);
            $("#triggerHorario").prop('disabled', false);

            $("#range-horario").attr({'min': -1});
            $("#range-horario").attr({'value': -1});
        } else {
            $("#metodologias-range").css("display","flex");
            $("#triggerMetodologia").prop('checked', false);

            $("#discussao-range").css("display","flex");
            $("#triggerDiscussao").prop('checked', false);

            $("#abordagem-range").css("display","flex");
            $("#triggerAbordagem").prop('checked', false);

            $("#avaliacao-range").css("display","flex");
            $("#triggerAvaliacao").prop('checked', false);

            $("#horario-range").css("display","flex");
            $("#triggerHorario").prop('checked', false);
        }
    })
</script>
@endsection
