@extends('layouts.app')

@section('title')
{{ $discipline->name }} - Portal das Disciplinas {{ $theme['PROJETO_SIGLA_SETOR']    }}
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/discipline.css')}}">
@endsection

@section('description')
@if (isset($discipline->professor->name))
{{ $discipline->name }} - {{ $discipline->code }}, tutorado por {{ $discipline->professor->name }}. Clique para saber
mais.
@else
{{ $discipline->name }} - {{ $discipline->code }}, tutorado por indefinido. Clique para saber

@endif
@endsection

@section('content')
<div class='banner text-center d-flex flex-column align-items-center justify-content-center  text-white'>
    <h1 class='display-title'>{{ $discipline->name }} - {{ $discipline->code }}</h1>
    @if(isset($discipline->emphase))
    <h3>{{$discipline->emphase->name}}</h3>
    @endif
</div>
@if(session('cadastroOK'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Cadastro realizado com sucesso</strong>
    <button type="button" class="close" aria-label="Close" data-dismiss="alert">
        <span aria-hidden="true"><strong>&times;</strong></span>
    </button>
</div>
@endif
<div class="container mt-4">
    <!-- Botão de cadastro FAQ -->

    @auth

    @if (Auth::user()->canDiscipline($discipline->id))
    <h3 class="mt-3">Menu do professor</h3>
    @if(isset($can) && $can)
    <button type="button" class="btn btn-outline-white text-white w-25 mt-2" data-toggle="modal" data-target="#faqs-create" style='background-color:#1155CC'>
        Registrar FAQ
    </button>
    @endif
    <form action=" {{route('disciplinas.edit', $discipline->id)}}" class="d-inline" method="get">
        @csrf
        @method('UPDATE')
        <button type="submit" class="btn btn-warning w-25 mt-2" value="Editar">Editar</button>
    </form>
    <form action=" {{route('disciplinas.destroy', $discipline->id)}}" class="d-inline" method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger w-25 mt-2" value="Apagar">Apagar</button>
    </form>
    @endif

    @endauth
    <!-- ROW Da PAGE -->
    <div class="row mt-5">
        <!-- main -->
        <div class="main col-md-12">
            <div class="row">
                <div class='col-md-8'>
                    <div class="section">
                        <h1 class="mb-3">Trailer</h1>
                        @if($discipline->has_trailer_media && $discipline->trailer->view_url != '')
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe style='border-radius: 6px;' class="embed-responsive-item" src="{{ $discipline->trailer->view_url}}" allowfullscreen></iframe>
                        </div>
                        @else
                        <img style='border-radius: 6px;' class="img-fluid" src="{{ asset('img/novideo1.png') }}" alt="Sem trailer">
                        @endif
                    </div>

                    <!-- SINOPSE -->
                    <div class="section mt-3">

                        <h1 class="mb-3">Sinopse</h1>
                        <div>
                            <div>
                                @if($discipline->description=='')
                                <div>
                                    <p>Não há sinopse cadastrada.</p>
                                </div>
                                @else
                                <div>
                                    <p style='text-align: justify; '>{{ $discipline->description}}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div><!--sinopse -->

                    <!-- VÍDEO -->
                    <div class='section'>
                        <h1 class="mb-3">Vídeo</h1>
                        @if($discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO) &&
                        $discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->view_url != '')
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe style='border-radius: 6px;' class="embed-responsive-item " allowfullscreen src="{{ $discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->view_url }}"></iframe>
                        </div>
                        @else
                        <img style='border-radius: 6px;' class="img-fluid" src="{{ asset('img/novideo2.png') }}" alt="Sem vídeo">
                        @endif
                    </div>{{--video--}}
                </div>
                <div class="col-md-4">
                    <div class='classifications'>
                        <h1>Classificações</h1>
                        @if (count($classifications)>0)
                        @foreach ( $classifications as $classification)
                        <div class='row mb-0'>
                            <div class="d-flex col-md-12 justify-content-center">
                                <label class="">
                                    <div class="d-flex">
                                        <h3 style='margin-bottom: 0;' class='smaller-p'>
                                            {{$classification->name ?? ''}}

                                            @if ($classification->description)
                                            <span data-toggle="tooltip" data-placement="top" title=" {{ $classification->description}}"><i class="far fa-question-circle"></i></span>
                                            @endif
                                        </h3>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="row d-flex align-items-center">
                            <div class="d-flex col-md-12">
                                <span class='d-flex justify-content-start' style='width:15%'><b>{{
                                $discipline->getClassificationsValues($classification->id) }}%</b></span>
                                <div class="progress " class='col-md-8' style="height: 20px; border-radius: 100px ; border: 2px solid black; padding: 2px; width:70%">
                                    <div id="{{$classification->classification_id}}" class="classification-color-left progress-bar" role="progressbar" style="width: {{ $discipline->getClassificationsValues($classification->id) }}%; border-radius: 100px 0 0 100px" aria-valuenow="0" aria-valuemin="0" aria-valuemax="20">
                                    </div>

                                    <div id="{{$classification->classification_id}}" class="classification-color-right progress-bar" role="progressbar" style="width: {{(100-$discipline->getClassificationsValues($classification->id))}}% ; border-radius: 0 100px 100px 0" aria-valuenow="0" aria-valuemin="0" aria-valuemax="20">
                                    </div>
                                </div>
                                <span class='d-flex justify-content-end' style='width:15%'><b>{{(100-number_format(($discipline->getClassificationsValues($classification->id)),1))}}%</b></span>
                            </div>
                        </div>

                        <div class="row ">
                            <div class="col-md-12 d-flex justify-content-between mt-2">
                                <span>
                                    <h3 style='margin-bottom: 0;' class='classification-text-left smaller-p'>{{
                                $classification->type_a ?? '' }}</h3>
                                </span>
                                <span>
                                    <h3 style='margin-bottom: 0; ' class='classification-text-right smaller-p'>{{
                                $classification->type_b ?? '' }}</h3>
                                </span>
                            </div>
                        </div>
                        @endforeach

                        @else
                        <strong>Não há classificações cadastradas.</strong>
                        @endif

                    </div>
                    <hr>
                    <!-- PODCAST -->
                    <div>
                        <h1 class=" mt-4 mb-2">Podcast</h1>
                        @if($discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST) &&
                        $discipline->getMediasByType(\App\Enums\MediaType::PODCAST)->first()->view_url != '')
                        <audio class="w-100" controls="controls">
                            <source src="{{ $discipline->getMediasByType(\App\Enums\MediaType::PODCAST)->first()->view_url}}" type="audio/mp3" />
                            seu navegador não suporta HTML5
                        </audio>
                        @else
                        <img class="img-fluid light-border-radius" src="{{asset('img/nopodcast.png') }}" alt="Sem podcast">
                        @endif
                    </div>
                    <hr>


                    <!-- MATERIAIS -->

                    <div class="mb-2">
                        <h1 class=" mt-4 mb-2 py-3">Materiais</h1>
                        @if($discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS) &&
                        $discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->view_url != '')
                        <div class="align-center">

                            <a href="{{ $discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->view_url}}" class="text">
                                <!-- <i class="fas fa-file-download fa-9x materiais-on"></i> -->
                                <button class="btn large-secondary-button my-3 w-100"> <i class="fas fa-file-download fa-lg mr-1"></i> Download</button>
                            </a>
                            <br />
                        </div>
                        @else
                        <div class="d-flex align-items-center">
                            <i class="fas fa-sad-tear fa-7x mr-3"></i>
                            <strong>Sem materiais disponiveis...</strong>
                        </div>

                        @endif
                    </div>
                    <hr>

                    <!-- Conhecimentos -->
                    <div class='section mt-3'>
                        <h1 class="mb-3">Conhecimentos / Competências Desejados</h1>
                        <div>
                            <div>
                                @if($discipline->acquirements=='')
                                <div class=" p-text">Nenhum conhecimento.</div>
                                @else
                                <div style="text-align: justify; line-height: normal;">{{ $discipline->acquirements }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class='section'>
                        <h1 class="mb-3">Obstáculos</h1>
                        <div>
                            <div>
                                @if($discipline->difficulties=='')
                                <div class=" p-text">Nenhum obstáculo.</div>
                                @else
                                <div style="text-align: justify; line-height: normal;">{{ $discipline->difficulties }}</div>
                                @endif
                            </div>
                        </div>

                    </div>
                    <div class="section">
                        <div class="card mt-5 px-2 py-2">
                            <div class="d-none">{{$actualYear = date("Y")}}</div>
                            <h1 class="mt-2">Índices de aprovação</h1>
                            <div class="form">
                                <div id="semesterSelectFields" class="form-group" style="opacity: 50%" ;>
                                    <div class="row d-flex align-items-end">
                                        <div class="col-md-3">
                                            <label>Ano Inicial</label>
                                            <select class="form-control form-control-sm disabled" id="yearStart" name="yearStart" onchange="onChangeSelect(event)" disabled>
                                                @for($i=$actualYear; $i > 2000;$i--)
                                                <option value='{{$i}}'>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Período Inicial</label>
                                            <select id="periodStart" name="periodStart" class="form-control form-control-sm" onchange="onChangeSelect(event)" disabled>
                                                <option value=1>1</option>
                                                <option value=2>2</option>
                                                <option value=3>3</option>
                                                <option value=4>4</option>
                                                <option value=5>5</option>
                                                <option value=6>6</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Ano Final</label>
                                            <select class="form-control form-control-sm" id="yearEnd" name="yearEnd" onchange="onChangeSelect(event)" disabled>
                                                @for($i=$actualYear; $i > 2000;$i--)
                                                <option value='{{$i}}'>{{$i}}</option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Período Final</label>
                                            <select id="periodEnd" name="periodEnd" class="form-control form-control-sm" onchange="onChangeSelect(event)" disabled>
                                                <option value=1>1</option>
                                                <option value=2>2</option>
                                                <option value=3>3</option>
                                                <option value=4>4</option>
                                                <option value=5>5</option>
                                                <option value=6>6</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <small class="text-danger" id="intervalErrorMessage"></small>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <input id="checkAllPeriods" name="checkAllPeriods" type="checkbox" checked onchange="onChangeCheckAllPeriods(event)">
                                    <label for="checkAllPeriods">Todos os períodos</label>
                                </div>
                                <div class="mt-1 d-flex flex-column" style="border-bottom: solid 1px rgba(0,0,0,0.2)">
                                    <button id="btnSearchDisciplineData" class="btn btn-primary mb-4" onclick="onSearchDisciplineDataClick('{{$discipline->code}}')">Buscar dados</button>
                                    <small id="infoBtnSearchDisciplineData" class=" ml-3 text-info d-none" style="text-align:center">Altere a data ou marque/desmarque a opção "Todos os períodos" para fazer uma nova busca.</small>
                                </div>

                                <div class="mt-3 px-1 d-flex flex-column" style="border:solid 1px rgba(0,0,0,0.2); border-radius: 5px;">
                                    <div>
                                        <input id="checkOnlyProfessorClasses" name="onlyProfessorClasses" type="checkbox" checked onchange="onChangeAllClasses(event)">
                                        <label for="checkOnlyProfessorClasses" style="cursor:pointer"><small>Somente turmas do professor</small></label>
                                    </div>
                                    <div>
                                        <input id="checkAllClasses" name="allClasses" type="checkbox" onchange="onChangeAllClasses(event)">
                                        <label for="checkAllClasses" style="cursor:pointer"><small>Turmas de todos os professores</small></label>
                                    </div>
                                </div>

                                <div class="mt-3 px-1" style="border:solid 1px rgba(0,0,0,0.2); border-radius: 5px;">
                                    <div id="form-group-select-class" class="form-group d-none">
                                        <label for="selectClass">Turma</label>
                                        <select id="selectClass" class="form-control form-control-sm" onchange="onSelectClass(event)">
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="infoPesquisaDados" class="alert alert-primary d-none" role="alert">
                                Buscando dados...
                            </div>
                            <div id="dadosDisciplina" class="mt-2 d-none container py-2 d-none" , style="border:solid 1px rgba(0,0,0,0.1); border-radius:5px">
                                <div class="row">
                                    <div class="col text-secondary">
                                        <h4 id="infoTipoBusca"></h4>
                                        <h4 id="infoNumDiscentes"></h4>
                                        <h4 id="infoProfessoresBusca"></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex justify-content-between">
                                            <strong class="text-success">Aprovados</strong>
                                            <span class="text-success"><b id="percentagemAprovados">0%</b></span>
                                        </div>
                                        <div class="progress">
                                            <div id="progressAprovados" class="progress-bar bg-success" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="d-flex justify-content-between">
                                            <strong class="text-danger">Reprovados</strong>
                                            <span class="text-danger"><b id="percentagemReprovados">0%</b></span>
                                        </div>
                                        <div class="progress">
                                            <div id="progressReprovados" class="progress-bar bg-danger" role="progressbar" style="width: 0%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2" style="background-color:azure">
                                    <strong class="col-6 mb-0 text-primary">Nota média<small class="text-secondary">&nbsp;(todas as unidades)</small></strong>
                                    <div class="col-6 d-flex justify-content-end">
                                        <strong id="notaMediaComponente" class="text-primary">0</strong>
                                    </div>
                                </div>

                                <div id="notasPorUnidade" class="row mt-2" style="border: 1px solid rgba(0,0,0,0.2); border-radius:5px"><!--Notas das unidades-->
                                    <div class="col-md-12">
                                        <strong class="text-secondary">Nota média por unidade</strong>
                                    </div>
                                    <div class="col-md-4 pb-3" style="box-sizing:border-box; border-radius:10px; box-shadow:2px 2px 5px rgba(0,0,0,0.2)">
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-secondary">Unidade 1</span>
                                                            <span id="notaUnidade1" class="text-secondary">N/A</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="progress">
                                                            <div id="progressNotaUnidade1" class="progress-bar bg-primary" role="progressbar" style="width: 10%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4" style="box-sizing:border-box; border-radius:10px; box-shadow:2px 2px 5px rgba(0,0,0,0.2)">
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-secondary">Unidade 2</span>
                                                            <span id="notaUnidade2" class="text-secondary">9.7</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="progress">
                                                            <div id="progressNotaUnidade2" class="progress-bar bg-primary" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" style="box-sizing:border-box; border-radius:10px; box-shadow:2px 2px 5px rgba(0,0,0,0.2)">
                                        <div class="row">
                                            <div class="col">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="d-flex justify-content-between">
                                                            <span class="text-secondary">Unidade 3</span>
                                                            <span id="notaUnidade3" class="text-secondary">4.2</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col">
                                                        <div class="progress">
                                                            <div id="progressNotaUnidade3" class="progress-bar bg-primary" role="progressbar" style="width: 42%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="section">
                        @if((isset($discipline->subjectTopics) && (count($discipline->subjectTopics ) > 0)) ||
                        (isset($discipline->subjectConcepts) && (count($discipline->subjectConcepts) > 0)) ||
                        (isset($discipline->subjectReferences) && (count($discipline->subjectReferences) > 0)))
                        <h1>Conteúdos</h1>
                        @if(isset($discipline->subjectTopics) && (count($discipline->subjectTopics)>0))
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h3 class="text-primary">Temas</h3>
                                @if(count($discipline->subjectTopics) > 3)
                                <a id="seeMoreTopics" class="link" data-toggle="collapse" href="#collapseTopics" role="button" aria-expanded="false" aria-controls="collapseTopics">
                                    ver mais
                                </a>
                                @endif
                            </div>
                            @if(count($discipline->subjectTopics) <= 3) <ul class="list-group list-group-flush">
                                @for($i = 0;$i < count($discipline->subjectTopics);$i++)
                                    <li class="list-group-item"><small>{{$discipline->subjectTopics[$i]->value}}</small></li>
                                    @endfor
                                    </ul>
                                    @else
                                    <ul class="list-group list-group-flush">
                                        @for($i = 0;$i < 3;$i++) <li class="list-group-item"><small>{{$discipline->subjectTopics[$i]->value}}</small></li>
                                            @endfor
                                    </ul>
                                    <div class="collapse" id="collapseTopics">
                                        <ul class="list-group list-group-flush">
                                            @for($i=3;$i < count($discipline->subjectTopics);$i++)
                                                <li class="list-group-item"><small>{{$discipline->subjectTopics[$i]->value}}</small></li>
                                                @endfor
                                        </ul>
                                    </div>
                                    @endif
                        </div>
                        @endif

                        @if(isset($discipline->subjectConcepts) && (count($discipline->subjectConcepts)>0))
                        <div class="card mt-2">
                            <div class="card-header d-flex justify-content-between">
                                <h3 class="text-primary">Conceitos</h3>
                                @if(count($discipline->subjectConcepts) > 3)
                                <a id="seeMoreConcepts" class="link" data-toggle="collapse" href="#collapseConcepts" role="button" aria-expanded="false" aria-controls="collapseConcepts">
                                    ver mais
                                </a>
                                @endif
                            </div>
                            @if(count($discipline->subjectConcepts) <= 3) <ul class="list-group list-group-flush">
                                @for($i = 0;$i < count($discipline->subjectConcepts);$i++)
                                    <li class="list-group-item"><small>{{$discipline->subjectConcepts[$i]->value}}</small></li>
                                    @endfor
                                    </ul>
                                    @else
                                    <ul class="list-group list-group-flush">
                                        @for($i = 0;$i < 3;$i++) <li class="list-group-item"><small>{{$discipline->subjectConcepts[$i]->value}}</small></li>
                                            @endfor
                                    </ul>
                                    <div class="collapse" id="collapseConcepts">
                                        <ul class="list-group list-group-flush">
                                            @for($i=3;$i < count($discipline->subjectConcepts);$i++)
                                                <li class="list-group-item"><small>{{$discipline->subjectConcepts[$i]->value}}</small></li>
                                                @endfor
                                        </ul>
                                    </div>
                                    @endif
                        </div>
                        @endif

                        @if(isset($discipline->subjectReferences) && (count($discipline->subjectReferences)>0))
                        <div class="card mt-2">
                            <div class="card-header d-flex justify-content-between">
                                <h3 class="text-primary">Referências</h3>
                                @if(count($discipline->subjectReferences) > 3)
                                <a id="seeMoreReferences" class="link" data-toggle="collapse" href="#collapseReferences" role="button" aria-expanded="false" aria-controls="collapseReferences">
                                    ver mais
                                </a>
                                @endif
                            </div>
                            @if(count($discipline->subjectReferences) <= 3) <ul class="list-group list-group-flush">
                                @for($i = 0;$i < count($discipline->subjectReferences);$i++)
                                    <li class="list-group-item"><small>{{$discipline->subjectReferences[$i]->value}}</small></li>
                                    @endfor
                                    </ul>
                                    @else
                                    <ul class="list-group list-group-flush">
                                        @for($i = 0;$i < 3;$i++) <li class="list-group-item"><small>{{$discipline->subjectReferences[$i]->value}}</small></li>
                                            @endfor
                                    </ul>
                                    <div class="collapse" id="collapseReferences">
                                        <ul class="list-group list-group-flush">
                                            @for($i=3;$i < count($discipline->subjectReferences);$i++)
                                                <li class="list-group-item"><small>{{$discipline->subjectReferences[$i]->value}}</small></li>
                                                @endfor
                                        </ul>
                                    </div>
                                    @endif
                        </div>
                        @endif
                        @else
                        <div class="card">
                            <h1>Conteúdos</h1>
                            <p1>Nenhum conteúdo cadastrado.</p1>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            <hr>

            <div class='section'>
                <h1 class="mb-3">Tópicos</h1>
                <ol type="I" id="discipline-topics">
                    @forelse ($discipline->topics as $topic)
                    @if (is_null($topic->parent_topic_id))
                    <li class="mb-3" id="topic-{{ $topic->id }}">
                        <span class="topic-title">{{ $topic->title }}</span>

                        <a class="ml-3 expand-topic" data-topic_id="{{ $topic->id }}" style="cursor: pointer; font-size: 14px;">
                            Mostrar mais
                        </a>

                        <br>

                        @if ($topic->required_level)
                        <small> Domínio desejado: {{ $topic->required_level }}</small>
                        @endif
                    </li>
                    @endif
                    @empty
                    <p>Sem tópicos cadastrados</p>
                    @endforelse
                </ol>
            </div>
            <div class="section">
                <h1>Metodologias</h1>
                @if(auth()->user())
                <div id="metodologias" class='d-flex'><span>carregando...</span></div>
                @if(Auth::user() && Auth::user()->professor && Auth::user()->professor->id == $discipline->professor->id)
                <button class="btn btn-success btn-sm mt-4" data-toggle="modal" data-target="#modal-cadastro-metodologia" onclick="openModalAddMethodologies()">
                    <i class="fas fa-solid fa-plus mr-2"></i>Adicionar nova metodologia
                </button>
                @endif
                <div id="modal-cadastro-metodologia" class="modal large fade" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Cadastro de metodologia</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <div class="row">
                                        <div class='col-md-12'>
                                            <button class="btn btn-primary btn-sm mb-2" style='cursor:pointer;' data-toggle="collapse" data-target="#collapse-criar-metodologia" onclick="clearCreateMethodologyInputs()">
                                                <i class="fas fa-solid fa-plus mr-1"></i>
                                                Criar metodologia
                                            </button>
                                        </div>
                                        <div class='col-md-12 mb-4'>
                                            <div id='collapse-criar-metodologia' class='collapse' class='form-group'>
                                                <label class="text-secondary" for="nome-nova-metodologia">Nome da metodologia</label>
                                                <input id="nome-nova-metodologia" type='text' class='form-control mb-1'>
                                                <label class="text-secondary" for="descricao-nova-metodologia">Descrição da metodologia</label>
                                                <textarea id="descricao-nova-metodologia" class='form-control mb-1' rows='6'></textarea>
                                                <p><small id="feedback-cadastro-methodology" class="d-none text-success form-text">Metodologia adicionada</small></p>
                                                <button class="btn btn-sm btn-outline-primary" onclick="btnCreateMethodology()">Criar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class='row'>
                                        <div class='col-md-12 card pt-2' id="methodologiesToChoose">
                                            <span class="text-info">carregando...</span>
                                        </div>
                                        <div class='col-md-12'>
                                            <small><strong id="feedback-add-methodology" class="d-none text-danger form-text">
                                                Selecione pelo menos uma metodologia.
                                            </strong></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-sm btn-success" onclick="addSelectedMethodologies()">Adicionar selecionados</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if(!auth()->user())
                @foreach($discipline->professor_methodologies as $professorMethodology)
                <strong class='badge badge-primary mr-2' style='cursor:pointer;' data-toggle='modal' data-target="{{'#modal-methodology' . $professorMethodology->id}}">
                    {{$professorMethodology->methodology->name}}
                </strong>
                <div class='modal fade' tabindex='-1' role='dialog' id="{{'modal-methodology' . $professorMethodology->id}}">
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class="modal-header">
                                <h3 class='modal-title text-primary'>{{$professorMethodology->methodology->name}}</h3>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <div class='d-flex flex-column'>
                                    <small class='text-secondary'>descrição da metodologia</small>
                                    <p class='text-primary'>{{$professorMethodology->methodology->description}}</p>
                                </div>
                                @if($professorMethodology->methodology_use_description && $professorMethodology->methodology_use_description != '')
                                <hr>
                                <div class='d-flex flex-column'>
                                    <small class='text-secondary'>Como o professor aplica a metodologia</small>
                                    <p class='text-primary'>{{$professorMethodology->methodology_use_description}}</p>
                                </div>
                                @endif

                            </div>
                            <div class='modal-footer'>
                                <button type='button' class='btn btn-sm btn-primary' data-dismiss='modal'>Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif

                <div class='modal fade' tabindex='-1' role='dialog' id='methodology-professor-view'>
                    <div class='modal-dialog' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h3 id='methodology-name' class='modal-title text-primary'></h3>
                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                    <span aria-hidden='true'>&times;</span>
                                </button>
                            </div>
                            <div class='modal-body'>
                                <div class='d-flex flex-column'>
                                    <div class='d-flex justify-content-end'>
                                        <button id="btn-remove-methodology" class='btn btn-outline-danger btn-sm' onclick='removeProfessorMethodology()'>
                                            Remover metodologia
                                        </button>

                                        <button id="btn-delete-methodology" class='btn btn-danger btn-sm ml-2' onclick='deleteMethodology()'>
                                            Apagar metodologia
                                        </button>

                                    </div>
                                    <div id='feedback-delete-methodology' class='alert alert-dismissible  d-none mt-2'>
                                        <small id='feedback-delete-methodology-message'>Não foi deletar a
                                            metodologia</small>
                                        <button class='close' onclick="closeAlert('feedback-delete-methodology')">&times</button>
                                        </small>
                                    </div>
                                    <small class='text-secondary'>descrição da metodologia</small>
                                    <textarea id='methodology-description' rows='9' class='text-primary'></textarea>
                                    <div id='feedback-methodology' class='d-none alert  mt-2'>
                                        <span id='feedback-methodology-message' style='text-align:center'>Erro ao
                                            atualizar</span>
                                        <button class='close' onclick="closeAlert('feedback-methodology')">&times</button>
                                    </div>
                                </div>
                                <hr>
                                <div class='d-flex flex-column'>
                                    <small class='text-secondary'>Como o professor aplica a metodologia</small>
                                    <textarea id='methodology-use-description' class='text-primary' rows='10' class="text-primary"></textarea>
                                    <div id='feedback-professor-methodology' class='d-none alert  mt-2'>
                                        <span id='feedback-professor-methodology-message' style='text-align:center'>Erro ao atualizar</span>
                                        <button class='close' onclick="closeAlert('feedback-professor-methodology')">&times</button>
                                    </div>
                                </div>
                            </div>
                            <div class='modal-footer'>
                                <button class='btn btn-success btn-sm' onclick='updateMethodologyAndProfessorMethodology(event)'>Salvar</button>
                                <button type='button' class='btn btn-sm btn-primary' data-dismiss='modal'>Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
</div>

<!-- FAQ -->
@if($discipline->faqs->count())
<div class="container">
    <h1 class="container-fluid  text-center mt-5">Perguntas Frequentes</h1>
    <div class="row mt-3" id="faqs">
        @foreach($discipline->faqs as $faq)
        <div class="w-100 card mb-3 text-dark " style='border:1px solid #014C8C;'>
            <div class="card-header" id="faq-header-{{$faq->id}}" data-toggle="collapse" data-target="#faq-content-{{$faq->id}}">
                <h5 class="mb-0 d-flex justify-content-between">
                    <button class="btn btn-link collapsed mr-auto" data-toggle="collapse" data-target="#faq-content-{{$faq->id}}" aria-expanded="true" aria-controls="faq-header-{{$faq->id}}">
                        {!! $faq->title !!}
                    </button>

                    @if(isset($can) && $can)
                    <form action=" {{route('disciplinas.faqs.destroy', [$discipline->id, $faq->id])}}" class="d-inline float-right" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mt-2" value="Apagar">Apagar</button>
                    </form>
                    @endif

                    <button class="btn btn-link collapsed ml-2" data-toggle="collapse" data-target="#faq-content-{{$faq->id}}" aria-expanded="true" aria-controls="faq-header-{{$faq->id}}">
                        <i class="fas fa-caret-down"></i>
                    </button>
                </h5>
            </div>

            <div id="faq-content-{{$faq->id}}" class="collapse" aria-labelledby="faq-header-{{$faq->id}}" data-parent="#faqs">
                <div class="card-body">
                    {!! $faq->content !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if(isset($can) && $can)
    @include('faqs.create_modal', ['discipline' => $discipline])
    @endif
</div>
@if (isset($discipline->professor->name))
<div class=" pt-4 pb-5" style=' margin-bottom: -3rem;'>

    <div class="container col-md-5">
        <div class="section">
            <h1 class="container-fluid  text-center mt-5">Faça uma pergunta!</h1>
            <!-- É necessário autenticar o  email do professor anteriormente -->

            <form id="formDuvida" action="https://formsubmit.co/eugenio@imd.ufrn.br" method="POST">
                <input type="hidden" name="_cc" value="{{ $discipline->professor->public_email }}" />
                <input type="hidden" name="_subject" value="Portal das Disciplinas - Nova requisição">
                <input type="hidden" name="_template" value="table">

                <input type="text" name="_honey" style="display:none">

                <div class="form-group">
                    <label for="studentEmail">Email</label>
                    <input type="email" id='studentEmail' name='Email do estudante' class="form-control" placeholder="Digite seu email" required>
                </div>
                <div class="form-group">
                    <label for="studentQuestion">Título</label>
                    <input type='text' id='studentQuestion' name='Título da pergunta' class="form-control" placeholder="Digite sua pergunta">
                </div>

                <div class="form-group">
                    <label for="studentQuestionDetails">Descrição da pergunta</label>
                    <textarea class="form-control" name='Detalhes' rows="3" placeholder="Forneça mais detalhes"></textarea>
                </div>
                <input type="hidden" name="_next" value='https://portaldasdisciplinas.imd.ufrn.br/disciplinas/{{$discipline->id}}'>
                <button class='blue-btn btn w-100' type="submit">Enviar pergunta</button>
            </form>
        </div>
    </div>

</div>
@endif
@if($errors->has('link'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>{{$errors->first('link')}}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true" style="font-size:25px">&times;</span>
    </button>
</div>
@endif
<div class="container mt-5"><!-- seção professor e créditos -->
    <div class="row g-5">
        @if (isset($discipline->professor->name))
        <div class="col">
            <div class="d-flex flex-row flex-wrap shadow justify-content-center  p-2">
                <div class="container">
                    <div class='section mb-5'>
                        <h1 class="mb-3">Professor</h1>
                        <div class="">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user fa-8x mr-4"></i>
                                <div class="wrapper-teacher-info">
                                    <div class="text-justify px-lg-3"> <strong>{{ $discipline->professor->name }}</strong> </div>
                                    <div class="text-justify px-lg-3"> <strong>Email: </strong>{{ $discipline->professor->public_email }} </div>
                                    @if($discipline->professor->rede_social1=='')
                                    <div class=" p-text"></div>
                                    @else
                                    <a href="{{ $discipline->professor->link_rsocial1 }}" class="text-justify px-lg-3"> <strong> {{ $discipline->professor->rede_social1 }} </strong></a>
                                    @endif
                                    @if($discipline->professor->rede_social2=='')
                                    <div class=" p-text"></div>
                                    @else
                                    <a href="{{ $discipline->professor->link_rsocial2 }}" class="text-justify px-lg-3"> <strong>{{ $discipline->professor->rede_social2 }}</strong></a>
                                    @endif
                                    @if($discipline->professor->rede_social3=='')
                                    <div class=" p-text"></div>
                                    @else
                                    <a href="{{ $discipline->professor->link_rsocial3 }}" class="text-justify px-lg-3"> <strong>{{ $discipline->professor->rede_social3 }}</strong></a>
                                    @endif
                                    @if($discipline->professor->rede_social4=='')
                                    <div class=" p-text"></div>
                                    @else
                                    <a href="{{ $discipline->professor->link_rsocial4 }}" class="text-justify px-lg-3"> <strong>{{ $discipline->professor->rede_social4 }}</strong></a>
                                    @endif

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- seção professor -->
        </div>
        @endif
        <!-- Seção créditos -->
        @if((Auth::user() && Auth::user()->isAdmin) || count($discipline->disciplineParticipants)>0)
        <div class="col">
            <div class="d-flex flex-column shadow p-2 align-items-start">
                <div class="d-flex flex-row justify-content-start align-items-baseline">
                    <h1 style="cursor:pointer" data-toggle="collapse" data-target="#collapseCreditos">Créditos <li class="fa fa-caret-down"></li>
                    </h1>
                    @if(Auth::user() && Auth::user()->isAdmin)
                    <button class="btn btn-success btn-sm ml-3 mb-4" data-toggle="modal" data-target="#modal-add"> &nbsp;+&nbsp; </button>
                    @endif
                </div>
                @foreach($discipline->disciplineParticipants as $participant)
                <div id="collapseCreditos" class="collapse w-100">
                    <div id="" class="d-flex flex-column mb-4" style="line-height:1.2">
                        <div class="d-flex flex-row align-items-center justify-content-between w-100 bg-pridmary">
                            <span class=" d-flex w-100 justify-content-between">
                                <strong class="" style="cursor:pointer" data-toggle="collapse" data-target="#linksCollapse{{$participant->id}}">
                                    {{$participant->name}}
                                    <li class="fas fa-caret-down"></li>
                                </strong>

                            </span>
                            @if(Auth::user() && Auth::user()->isAdmin)
                            <div class="d-flex align-items-center">
                                <button class="ml-1  btn btn-link" id="{{$loop->index}}" onclick="openModalEdit(event)" data-toggle="modal" data-target="#modal-edit">editar</button>
                                <form class="" action="{{route('participants_discipline.destroy',$participant->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button class=" mr-0 p-0 text-danger btn btn-link" type="submit">remover</button>
                                </form>
                            </div>
                            @endif

                        </div>

                        <div class="collapse card" id="linksCollapse{{$participant->id}}">
                            <small>
                                <strong><i>{{$participant->role}}</i></strong>
                                @if(isset($participant->email) && $participant->email != "")
                                <a href="mailto:{{$participant->email}}" class="ml-3">e-mail</a>
                                @if(count($participant->links)>0)
                                <span class="text-primary">&nbsp;|</span>
                                @endif
                                @endif

                                @foreach($participant->links as $link)
                                <a href="{{$link->url}}" rel="noopener" target="_blank" class="ml-2">{{$link->name}}</a>
                                @if(!$loop->last)
                                <span class="text-primary">&nbsp;|</span>
                                @endif
                                @endforeach

                            </small>
                        </div>
                    </div>
                </div><!--collapse-->
                @endforeach
            </div><!--Seção créditos -->
        </div> <!--col-->
        @endif
    </div>
</div><!-- seção professor e créditos -->

<div class="modal fade" id="modal-add" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Cadastro de participante</h2>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('participants_discipline.store')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input class="form-control" id="name" name="name" type="text" placeholder="Nome do participante" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Função</label>
                        <input class="form-control" id="role" name="role" type="text" placeholder="Função do participante nesta disciplina" maxlength=20 required>
                    </div>
                    <div class="form-group">
                        <label for="role">E-mail</label>
                        <input class="form-control" id="email" name="email" type="email" placeholder="E-mail" required>
                    </div>
                    <label>Links</label>
                    <div class="mb-1" id="links"><!--links -->
                        <!-- Conteudo dinâmico gerado por javascript-->
                        <!-- renderLinks() -->

                    </div><!--links -->
                    <input id="submit-form" type="submit" hidden>
                    <input name="idDiscipline" type=text value="{{$discipline->id}}" hidden>
                </form>
                <button id="add-link-field" class="btn btn-outline-primary btn-sm" onclick="addLinkField('modal-add')">Adicionar Link</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <label for="submit-form" class="btn btn-primary">Cadastrar</label>
            </div>
        </div>
    </div>
</div><!--modal-add -->


<div class="modal fade" id="modal-edit" role="dialog"><!--modal edit -->
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edição de participante</h2>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('participants_discipline.update')}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nome</label>
                        <input class="form-control" id="nameEdit" name="name" type="text" placeholder="Nome do participante" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Função</label>
                        <input class="form-control" id="role" name="role" type="text" placeholder="Função do participante desta disciplina" maxlength=30 required>
                    </div>
                    <div class="form-group">
                        <label for="role">E-mail</label>
                        <input class="form-control" id="email" name="email" type="email" placeholder="E-mail" required>
                    </div>
                    <label>Links</label>
                    <!-- Conteudo dinâmico gerado por javascript-->
                    <!-- renderLinks() -->
                    <div class="mb-1" id="links">
                    </div><!--links -->
                    <input id="submit-form-update" type="submit" hidden>
                    <input name="idDiscipline" type='text' value="{{$discipline->id}}" hidden>
                    <input name="idParticipant" type="text" hidden>
                </form>
                <button id="add-link-field" onclick="addLinkField('modal-edit')" class="btn btn-outline-primary btn-sm">Adicionar Link</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <label for="submit-form-update" class="btn btn-primary">Atualizar</label>
            </div>
        </div>
    </div>
</div><!--modal-edit -->

<div id="modalAlertLinks" class="modal show fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary">
                <h3 class="text-white">Erro</h3>
            </div>
            <div class="modal-body bg-secondary text-white">
                <span>O participante pode ter até 3 links</span>
            </div>
            <div class="modal-footer bg-secondary">
                <button class="btn btn-primary" data-dismiss="modal">Entendido</button>
            </div>
        </div>

    </div>
</div>

<script>
    let links = [];

    function renderLinks(idModal) {

        var html = "";
        for (var i = 0; i < links.length; i++) {
            html += "<div class='form-group'>" +
                "<input class='form-control' name='link-name[]' type='text' placeholder='Instragram, Twitter, Facebook, etc...'" +
                " value='" + links[i].linkName + "' required maxlength=20>" +
                "<input class='form-control mt-1' name='link-url[]' type='text' placeholder='Url do link' " +
                " value='" + links[i].linkUrl + "' required >" +
                /* label id=i servirá para armazenar o índice do elemento no array links */
                "<label id='" + i + "' class='btn btn-link mb-4 mt-0 p-0' " + "onclick='deleteFieldLink(event,\"" + idModal + "\")'" + "> remover </label>" +
                "</div>";
        }

        return html;
    }

    function addLinkField(modalId) {

        let linkFields = document.querySelectorAll("#" + modalId + " #links .form-group");
        if (linkFields.length > 2) {
            $('#modalAlertLinks').modal('show');
            return;
        }


        var linkNames = document.querySelectorAll("#" + modalId + " input[name='link-name[]']");
        var linkUrls = document.querySelectorAll("#" + modalId + " input[name='link-url[]']");
        for (var i = 0; i < linkNames.length; i++) {
            links[i] = {
                linkId: i,
                linkName: linkNames[i].value,
                linkUrl: linkUrls[i].value
            };
        }
        var element = {
            linkName: "",
            linkUrl: ""
        };
        links.push(element);
        document.querySelector("#" + modalId + " #links").innerHTML = renderLinks(modalId);
    }

    function deleteFieldLink(event, modalId) {

        var linkNames = document.querySelectorAll("#" + modalId + " input[name='link-name[]']");
        var linkUrls = document.querySelectorAll("#" + modalId + " input[name='link-url[]']");
        for (var i = 0; i < linkNames.length; i++) {
            links[i] = {
                linkId: i,
                linkName: linkNames[i].value,
                linkUrl: linkUrls[i].value
            };
        }
        var index = event.target.id;
        links = links.filter(item => index != item.linkId);

        for (var i = 0; i < links.length; i++) {
            links[i].linkId = i;
        }

        document.querySelector("#" + modalId + " #links").innerHTML = renderLinks(modalId);
    }

    function openModalEdit(event) {
        links = [];
        var discipline = @json($discipline);
        var selectedParticipant = discipline.discipline_participants[event.target.id];
        document.querySelector("#modal-edit input[name='idParticipant']").value = selectedParticipant.id;
        document.querySelector("#modal-edit input[name='name'").value = selectedParticipant.name;
        document.querySelector("#modal-edit input[name='role'").value = selectedParticipant.role;
        document.querySelector("#modal-edit input[name='email'").value = selectedParticipant.email;


        selectedParticipant.links.forEach(function(link, i) {
            var element = {
                linkId: i,
                linkName: link.name,
                linkUrl: link.url
            };
            links.push(element);
        });

        document.querySelector("#modal-edit #links").innerHTML = renderLinks('modal-edit');
    }


    // Scripts referente aos tópicos    
    $(document).on('click', '.expand-topic', function() {
        let topicId = $(this).data('topic_id');
        let disciplineId = '{{$discipline->id}}';
        let topicElement = $(`#topic-${topicId}`);

        if ($(this).hasClass('expanded')) {
            $(`#topic-${topicId}-controls`).remove();
            $(this).html('Mostrar Mais');
            $(this).removeClass('expanded');
            $(`#topic-${topicId}-subtopics`).remove();
        } else {
            $(this).addClass('expanded');

            $(this).html('Mostrar menos');

            $.ajax({
                method: "GET",
                url: `/discipline/${disciplineId}/topic/${topicId}/subtopics`,
                success: function(html) {
                    topicElement.append(html);
                }
            });
        }
    });
</script>


@endsection
@section('scripts-bottom')
<script>
    let professorName = "{{$discipline->professor->name}}".toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g, "");
</script>
<script src="{{asset('js/disciplinePerfomanceDataFormPortal.js')}}"></script>
<script src="{{asset('js/subjectContentsCollapse.js')}}"></script>
<script>
    let disciplineId = "{{$discipline->id}}";
    let disciplineCode = "{{$discipline->code}}";
    let professorId = "{{$discipline->professor->id}}";
    let userIdProfessor = null;
    @if(auth()->user() && auth()->user()->isProfessor)
    userIdProfessor = '{{Auth::user()->professor->id}}';
    @endif
</script>
<script src="{{asset('js/methodologies.js')}}"></script>
<script>
    @if(auth()->user())
    getProfessorMethodologies();
    let token = '{{csrf_token()}}';
    @endif
</script>
<script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    searchDisciplineData("{{$discipline->code}}");
</script>

@endsection