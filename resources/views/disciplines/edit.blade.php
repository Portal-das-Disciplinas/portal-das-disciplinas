@extends('layouts.app')

@section('title')
Editar disciplina - Portal das Disciplinas
@endsection

@section('robots')
noindex, follow
@endsection

@section('content')

<div class="container">
    <div class='page-title'>
        <h1>Editar disciplina</h1>
    </div>
    
    @if($errors->any())
    @foreach($errors->all() as $error)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <li>{{$error}}</li>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endforeach
    @endif

    <h4 class="text-center m-4"></h4>
    <div class=" font-weight-bold">
        <form action="{{ route('disciplinas.update' , $discipline->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group col-md-10">
                    <label for="name">
                        Nome da disciplina
                    </label>
                    <input type="text" required class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}" id="name" name="name" value="{{$discipline->name}}" placeholder="Estrutura de dados básica I">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group col-md-2">
                    <label for="code">
                        Código
                    </label>
                    <input type="text" required class="form-control {{ $errors->has('code') ? 'is-invalid' : ''}}" id="code" name="code" value="{{$discipline->code}}" placeholder="IMD0000">
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="col-md-12 px-0">

                <label class="" for="emphasis">
                    Ênfase da disciplina
                </label>
                <select name="emphasis" id="emphasis" class='form-control'>
                    <option  value=""> Nehuma </option>
                    @foreach($emphasis as $emphase)
                    <option value="{{ $emphase->id }}" @if(isset($discipline->emphasis_id) && $emphase->id == $discipline->emphasis_id) selected @endif>{{ $emphase->name }}</option>
                    @endforeach
                </select>

               


            </div>
            <div class="col-md-12 px-0 pr-0">
                <label for="professor">Professor</label>
                @if (Auth::user()->is_admin)
                <div class="form-group">
                    <select name="professor" id="professor" class="form-control" aria-label="Professor">
                        @foreach ($professors as $professor)
                        @if ($professor->id == $discipline->professor_id)
                        <option selected="selected" value="{{$professor->id}}">{{$professor->name}}</option>
                        @else
                        <option value="{{$professor->id}}">{{$professor->name}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            <div class="form-row mt-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="d-flex">
                            <label for="description">
                                Sinopse
                            </label>
                            <p data-toggle="tooltip" data-placement="top" title="Principais pontos da disciplina."><i class="far fa-question-circle ml-1"></i></p>
                        </div>

                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : ''}}" id="description" name="description" rows="8" placeholder="Explique aqui como funciona a disciplina">{{$discipline->description}}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="difficulties">
                                Obstáculos
                            </label>
                            <p data-toggle="tooltip" data-placement="top" title="Pontos que podem ou costumam ser dificultosos na disciplina.
                            "><i class="far fa-question-circle ml-1"></i></p>
                        </div>
                        <div class="input-group">
                            <textarea class="form-control {{ $errors->has('difficulties') ? 'is-invalid' : ''}}" id="difficulties" name="difficulties" rows="8" placeholder="Coloque aqui problemas que alunos costumam relatar ao cursar esse componente.">{{$discipline->difficulties}}</textarea>
                            @error('difficulties')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card p-2 mt-2" style="background-color: #F0F8FF">
                        <div id="area-edit-topics" class="card">
                            <span>Edição dos Tópicos</span>
                            <div id="area-fields-topics">
                                @foreach($discipline->subjectTopics as $key=>$topic)
                                <div id="{{'topic-' . $key}}" class="form-group">
                                    <textarea name="topics[]" type="text" class="form-control">{{$topic->value}}</textarea>
                                    <input name="topicsId[]" type="hidden" value="{{$topic->id}}">
                                    <div class="d-flex justify-content-end">
                                        <small class="text-danger" style="cursor:pointer" onclick="removeTopicField(event)">remover</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <span class="btn btn-primary btn-sm" onclick="addTopicField()">Adicionar campo</span>
                            </div>
                        </div>

                        <div id="area-edit-concepts" class="px-1 mt-2 card">
                            <span>Edição dos conceitos</span>
                            <div id="area-fields-concepts">
                                @foreach($discipline->subjectConcepts as $key=>$concept)
                                <div id="{{'concept-' . $key}}" class="form-group">
                                    <textarea name="concepts[]" type="text" class="form-control">{{$concept->value}}</textarea>
                                    <input name="conceptsId[]" type="hidden" value="{{$concept->id}}">
                                    <div class="d-flex justify-content-end">
                                        <small class="text-danger" style="cursor:pointer" onclick="removeConceptField(event)">remover</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <span class="btn btn-primary btn-sm" onclick="addConceptField()">Adicionar campo</span>
                            </div>
                        </div>

                        <div id="area-edit-references" class="px-1 mt-2 card">
                            <span>Edição das referências</span>
                            <div id="area-fields-references">
                                @foreach($discipline->subjectReferences as $key=>$reference)
                                <div id="{{'reference-' . $key}}" class="form-group">
                                    <textarea name="references[]" type="text" class="form-control">{{$reference->value}}</textarea>
                                    <input name="referencesId[]" type="hidden" value="{{$reference->id}}">
                                    <div class="d-flex justify-content-end">
                                        <small class="text-danger" style="cursor:pointer" onclick="removeReferenceField(event)">remover</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="d-flex justify-content-end">
                                <span class="btn btn-primary btn-sm" onclick="addReferenceField()">Adicionar campo</span>
                            </div>
                        </div>

                    </div>
                </div>
                {{-- TODO
                Card de midias com "x" para excluir --}}
                <div class="col-md-6 col-sm-12">
                    <div class="form-group font-weight-normal">
                        <div class="d-flex justify-content-between">
                            <label class="font-weight-bold">Classificações</label>
                            <p data-toggle="tooltip" data-placement="top" title="Deslize os sliders e quantifique em porcentagem o quanto a sua disciplina se encaixa na referida classificação">Ajuda <i class="far fa-question-circle"></i></p>
                        </div>
                        @if (count($classifications) > 0)
                        @foreach ( $classifications as $classification)
                        <!-- COMPONENTE DO INPUT DE CLASSIFICAO -->
                        <div class="classification-input-component" id='1'>
                            <div>
                                <h3 class='smaller-p text-center'>{{$classification->name}}</h3>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <div><span>{{$discipline->getClassificationsValues($classification->id)}}</span>%</div>
                                </div>
                                <div class="slider-container">
                                    <input id="classification-slider" name="classification-{{ $classification->id }}" type="range" min="0" max="100" value="{{$discipline->getClassificationsValues($classification->id)}}" step='5' class="classification-slider scrollClass classification-{{$classification->id}}" oninput="handleInput(this.value, this)">
                                </div>
                                <div>
                                    <div><span>{{100-$discipline->getClassificationsValues($classification->id)}}</span>%</div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between;" class="classification-subtitiles">
                                <h3 class='smaller-p'>{{ $classification->type_a ?? '' }}</h3>
                                <h3 class='smaller-p'>{{ $classification->type_b ?? '' }}</h3>

                            </div>
                        </div>
                        <!-- FIM DO COMPONENTE DO INPUT DE CLASSIFICAO -->
                        <!-- <div class="row ">
                                    <div class=" d-flex justify-content-center col-md-12">
                                        <h5>{{ $classification->name }}
                                            @if ($classification->description)
                                                <span data-toggle="tooltip" class="h4" data-placement="top" title=" {{ $classification->description}}"><i class="far fa-question-circle" ></i></span>   
                                            @endif
                                        </h5>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class=" col-md-2">
                                        <output id="outMetodologia">{{$discipline->getClassificationsValues($classification->id)}}</output><span>%<span>
                                    </div>
                                    <div class="col-md-8">
                                        <input class='w-100' id="{{ $classification->id }}" name="{{ $classification->name }}" type="range" step="5" value="{{$discipline->getClassificationsValues($classification->id)}}" min="0" max="100" oninput="handleInput(outMetodologia, outMetodologiaSecondary, this.value)" >      
                                    </div>
                                    <div class=" col-md-2 d-flex justify-content-end ">
                                    <div>
                                        <output id="outMetodologiaSecondary" >{{100-$discipline->getClassificationsValues($classification->id)}}</output><span>%<span>
                                    </div>
                                        
                                    </div>
                                </div>
                                <div class="legend row">
                                    <div class="d-flex col-md-12 justify-content-between">
                                        <p>{{ $classification->type_a ?? '' }}</p>
                                        <p>{{ $classification->type_b ?? '' }}</p>
                                    </div>
                                </div> -->
                        @endforeach
                        @else
                        <p>Não há classificações cadastradas.</p>
                        @endif



                        {{-- TODO --}}
                        {{-- tentar fazer texto aparecer abaixo do range --}}
                        <datalist id="tickmarks" style="--list-length: 9;">
                            <option value="0">1</option>
                            <option value="1">2</option>
                            <option value="2">A</option>
                            <option value="3">B</option>
                            <option value="4">C</option>
                            <option value="5">C</option>
                            <option value="6">C</option>
                        </datalist>
                        @error('classificacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="media-trailer">
                                Trailer da disciplina
                            </label>
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Razões pelas quais esta disciplina pode ser para você."><i class="far fa-question-circle ml-1"></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control {{ $errors->has('media-trailer') ? 'is-invalid' : ''}}" name="media-trailer" id="media-trailer" @if ($discipline->has_trailer_media)
                            value="{{$discipline->trailer->url}}"
                            @endif
                            aria-describedby="basic-addon3"
                            placeholder="Link para vídeo no Youtube">
                            @error('media-trailer')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="media-video">
                                Vídeo
                            </label>
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Bate papo entre professores e alunos sobre os principais aspectos da disciplina."><i class="far fa-question-circle ml-1"></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control {{ $errors->has('media-video') ? 'is-invalid' : ''}}" name="media-video" id="media-video" @if ($discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO))
                            value="{{$discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->url}}"
                            @endif
                            aria-describedby="basic-addon3"
                            placeholder="Link para vídeo no Youtube">
                            @error('media-video')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="media-podcast">
                                Podcast
                            </label>
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Pode ser ouvido com o celular travado. Bate papo entre professores e alunos sobre a disciplina."><i class="far fa-question-circle ml-1"></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control {{ $errors->has('media-podcast') ? 'is-invalid' : ''}}" name="media-podcast" id="media-podcast" @if ($discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST))
                            value="{{$discipline->getMediasByType(\App\Enums\MediaType::PODCAST)->first()->url}}"
                            @endif
                            aria-describedby="basic-addon3"
                            placeholder="Link para podcast no Youtube">
                            @error('media-podcast')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="media-material">
                                Materiais
                            </label>
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Amostra de apostilas, avaliações e outros materiais da disciplina."><i class="far fa-question-circle ml-1"></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control {{ $errors->has('media-material') ? 'is-invalid' : ''}}" name="media-material" id="media-material" @if ($discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS))
                            value="{{$discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->url}}"
                            @endif
                            aria-describedby="basic-addon3"
                            placeholder="Link para arquivo no Google Drive">
                            @error('media-material')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="acquirements">
                                Conhecimentos/Competência Desejados
                            </label>
                            <p data-toggle="tooltip" data-placement="top" title="Conhecimentos e habilidades necessários ou interessantes para que o aluno possa fazer matricula na disciplina.
                            "><i class="far fa-question-circle ml-1"></i></p>
                        </div>
                        <div class="input-group">
                            <textarea class="form-control {{ $errors->has('acquirements') ? 'is-invalid' : ''}}" id="acquirements" name="acquirements" rows="8" placeholder="Coloque aqui conhecimentos desejaveis para o aluno cursar a disciplina.">{{$discipline->acquirements}}</textarea>
                            @error('acquirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-row mt-3">
                <div class="form-group">
                    <div class="d-flex">
                        <label for="">Tópicos</label>
                        <p data-toggle="tooltip" data-placement="top" title="Conhecimentos e habilidades necessários ou interessantes para que o aluno possa fazer matricula na disciplina
                        "><i class="far fa-question-circle ml-1"></i></p>
                    </div>
                    <div>
                        <ol type="I" id="discipline-topics">
                            @forelse ($discipline->topics as $topic)
                                @if (is_null($topic->parent_topic_id))
                                    <li class="mb-3" id="topic-{{ $topic->id }}">
                                        <span class="topic-title">{{ $topic->title }}</span>

                                        <a 
                                            class="ml-3 expand-topic" 
                                            data-topic_id="{{ $topic->id }}" 
                                            style="cursor: pointer; font-size: 14px;"
                                        >
                                            Mostrar mais
                                        </a>

                                        <br>

                                        @if ($topic->required_level)
                                            <small> Domínio desejado: <span class="topic-level">{{  $topic->required_level }}</span></small>
                                        @endif
                                    </li>
                                @endif
                            @empty
                                <p>Sem tópicos cadastrados</p>
                            @endforelse
                        </ol>
                        <button type="button" class="mt-2 btn btn-outline-primary add-topic">Adicionar</button>
                    </div>
                </div>
            </div>

            <div class='page-title'>
                <h3>Perguntas Frequentes</h3>
            </div>
            <div id="faqs"><!-- Conteúdo gerado por javascript --></div>
            <button class="btn btn-primary" onclick="addFaqField(event)">Adicionar FAQ</button>



            <h3 class="page-title">Créditos</h3>
            <div class="container-fluid card pt-3 pb-3">
                <div class="row">
                    <div class="col" id="participants"></div>

                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary" onclick="addParticipantField(event)">
                            Adicionar participante
                        </button>
                    </div>
                </div>
                <input id="participantsList" name="participantList" hidden>

            </div>
            <div id="modalLinksLimit" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Erro</h3>
                        </div>
                        <div class="modal-body bg-warning">
                            <p>Número máximo de links alcançado.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row d-flex p-2 mt-3 justify-content-center">
                <a href="{{ route('home') }}" class="btn btn-danger btn-sm">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-sm ml-5">Salvar alterações</button>
            </div>
        </form>
    </div>


</div>





@php
$classificationsJson = json_encode($classifications);
@endphp
@include('faqs.create_modal', ['discipline' => $discipline])

@endsection
@section('scripts-bottom')
<script src="{{asset('js/disciplines.js')}}"></script>
<script src="{{asset('js/subjectContentsEdit.js')}}"></script>

<script>
    //let classifications = JSON.parse('{!! $classificationsJson !!}');
    let classifications = @json($classifications);         
    function handleInput(value, element) {
        const sliderContainer = element.parentNode
        const leftOutput = sliderContainer.previousElementSibling.querySelector('span')
        const rightOutput = sliderContainer.nextElementSibling.querySelector('span')

        leftOutput.innerText = value
        rightOutput.innerText = 100 - value
    }

    const sliderId = document.querySelector('#classification-slider')
    const leftOutputs = document.querySelectorAll('#left-output-value')
    const rightOutputs = document.querySelectorAll('#right-output-value')

    for (leftOutput of leftOutputs) {
        leftOutput.innerText = sliderId.value
    }

    for (rightOutput of rightOutputs) {
        rightOutput.innerText = sliderId.value
    }

    /* tooltip initialize */
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })


    /*scripts relacionados com a adição das faqs */

    let faqs = @json($discipline->faqs);

    function addFaqField(event) {
        event.preventDefault();
        faqs.push({
            title: "",
            content: ""
        });
        renderFaqs("#faqs");
    }

    function removeFaqField(index) {
        event.preventDefault();
        faqs = faqs.filter(function(faq, idx) {
            return idx != index;
        });

        renderFaqs("#faqs");
    }

    function onchangeTitle(event, index) {
        faqs[index].title = event.target.value;
    }

    function onchangeContent(event, index) {
        faqs[index].content = event.target.value;
    }



    function renderFaqs(target) {
        let html = "";
        faqs.forEach(function(faq, index) {
            html += "<div class='form card p-1 mb-2' style='background-color:#f2f2f2'>" +
                "<input name='faqId[]' hidden value='" + faq.id + "'>" +
                "<label>Pergunta</label>" +
                "<input class='form-control mb-1' name='faqTitle[]' type='text' value='" + faq.title + "' onchange='onchangeTitle(event," + index + ")' required>" +
                "<label>Resposta</label>" +
                "<textarea class='form-control' name='faqContent[]' onchange='onchangeContent(event," + index + ")' required>" + faq.content + "</textarea>" +
                "<div class='d-flex justify-content-end'>" +
                "<label onclick='removeFaqField(" + index + ")' class='text-danger' style='cursor:pointer'>remover</label>" +
                "</div>" +
                "</div>";
        });

        document.querySelector(target).innerHTML = html;

    }

    renderFaqs('#faqs');

    //Scripts relacionados com a adição de participantes da disciplina
    let data = @json($participants);
    setParticipants(data);
    sendParticipantsToFormInput();
    renderParticipants('#participants');


    // Scripts referente á adição de tópicos
    let counter = 1;
    let newTopicBtn = document.querySelector('.add-topic');

    $(document).on('click', '.add-topic', function() {
        let topicsList = document.getElementById('discipline-topics');
        let parentTopic = null;

        if ($(this).hasClass('subtopic-control')) {
            let topicId = $(this).data('topic_id');
            topicsList = document.getElementById(`topic-${topicId}-subtopics`);
            parentTopic = topicId;
        }

        let newTopicForm = `
        <div class="container" id="topic-form-${counter}">
            <div class="form-group">
                <div class="d-flex">
                    <label for="title-edit">
                        Título
                    </label>
                    <p data-toggle="tooltip" data-placement="top" title="Título do tópico"><i class="far fa-question-circle ml-1"></i></p>
                </div>
                <div class="input-group">
                    <input class="form-control topic-inputs-${counter}" type="text" placeholder="Título do tópico" />
                </div>
            </div>

            <div class="form-group">
                <div class="d-flex">
                    <label for="level">
                        Domínio desejado
                    </label>
                    <p data-toggle="tooltip" data-placement="top" title="Domínio de conhecimento que deseja que o aluno possua"><i class="far fa-question-circle ml-1"></i></p>
                </div>
                <div class="input-group mt-2">
                    <select class="form-control topic-inputs-${counter}">
                        <option value="0">Não exige domínio</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </div>
                <div class="d-flex mt-3">
                    <button type="button" data-target="#topic-form-${counter}" class="remove-topic-form btn text-danger mr-2">Cancelar</button>
                    <button type="button" class="save-topic btn text-primary" data-inputs-target="topic-inputs-${counter}">Salvar</button>
                </div>
            </div>
        </div>
        `;        
        
        $(topicsList).append(newTopicForm);
        counter += 1;

        // Evitar que o click dispare mais de uma vez
        $('.save-topic').unbind('click').click(function() {
            let topicData = $(this).data('inputs-target');
            let [ topicTitle, topicLevel ] = $(`.${topicData}`);
            let disciplineId = "{{ $discipline->id }}";
            
            $.ajax({
                url: "{{route('topic.store')}}",
                method: 'POST',
                data: {
                    title: $(topicTitle).val(),
                    required_level: $(topicLevel).val(),
                    discipline_id: disciplineId,
                    parent_topic_id: parentTopic
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function(e) {
                    console.log(e);
                },
                success: function(result) {
                    location.reload(); 
                }

            });
        });

        $('.remove-topic-form').unbind('click').click(function() {
            let topicFormId = $(this).data('target');
            $(topicFormId).remove();
        });
    });
    
    $(document).on('click', '.expand-topic', function() {
        let topicId = $(this).data('topic_id');
        let disciplineId = {{ $discipline->id }};
        let topicElement = $(`#topic-${topicId}`);

        if ($(this).hasClass('expanded')) {
            $(`#topic-${topicId}-controls`).remove();
            $(this).html('Mostrar Mais');
            $(this).removeClass('expanded');
            $(`#topic-${topicId}-subtopics`).remove();
        } else {
            $(this).addClass('expanded');

            $(this).html('Mostrar menos');
            $(topicElement).append(`
            <div class="d-flex my-3" id="topic-${topicId}-controls">
                <button type="button" class="add-topic subtopic-control btn btn-outline-success mr-3" title="Adicionar subtópico" data-topic_id="${topicId}">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>
                <button type="button" class="edit-topic btn btn-outline-primary mr-3" title="Editar tópico" data-topic_id="${topicId}">
                    <i class="fa fa-wrench" aria-hidden="true"></i>
                </button>
                <button type="button" class="delete-topic  btn btn-outline-danger" title="Remover tópico" data-topic_id="${topicId}">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
            </div>
            `);

            $.ajax({
                method: "GET",
                url: `/discipline/${disciplineId}/topic/${topicId}/subtopics`,
                data: {
                        caller: "{{ Route::currentRouteName() }}"
                },
                success: function(html) {
                    topicElement.append(html);
                }
            });
        }

        $('.delete-topic').click(function() {
            let topicToRemove = $(this).data('topic_id');

            $.ajax({
                method: "DELETE",
                url: `/discipline/${disciplineId}/topic/${topicId}/delete`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    location.reload(); 
                }
            });
        });

        let counterEdit = 0;

        $('.edit-topic').click(function() {
            counterEdit += 1;

            let topicTitle = $(topicElement).children('span.topic-title').text();
            let topicLevel = $(topicElement).children('small').children('span.topic-level').text();

            // Param true para não remover os eventos
            let initialHtml = $(topicElement).children().clone(true);

            let editTopicForm = `
            <div class="container" id="topic-edit-form-${counterEdit}">
                <div class="form-group">
                    <div class="d-flex">
                        <label for="title-edit">
                            Título
                        </label>
                        <p data-toggle="tooltip" data-placement="top" title="Título do tópico"><i class="far fa-question-circle ml-1"></i></p>
                    </div>
                    <div class="input-group">
                        <input class="form-control" id="title-edit-${counterEdit}" placeholder="Título do tópico" value="${topicTitle}" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="d-flex">
                        <label for="level">
                            Domínio desejado
                        </label>
                        <p data-toggle="tooltip" data-placement="top" title="Domínio de conhecimento que deseja que o aluno possua"><i class="far fa-question-circle ml-1"></i></p>
                    </div>
                    <div class="input-group mt-2">
                        <select class="form-control" id="level-edit-${counterEdit}">
                            <option value="0" ${topicLevel == "0" ? 'selected':''}>Não exige domínio</option>
                            <option value="1" ${topicLevel == "1" ? 'selected':''}>1</option>
                            <option value="2" ${topicLevel == "2" ? 'selected':''}>2</option>
                            <option value="3" ${topicLevel == "3" ? 'selected':''}>3</option>
                            <option value="4" ${topicLevel == "4" ? 'selected':''}>4</option>
                            <option value="5" ${topicLevel == "5" ? 'selected':''}>5</option>
                            <option value="6" ${topicLevel == "6" ? 'selected':''}>6</option>
                            <option value="7" ${topicLevel == "7" ? 'selected':''}>7</option>
                            <option value="8" ${topicLevel == "8" ? 'selected':''}>8</option>
                            <option value="9" ${topicLevel == "9" ? 'selected':''}>9</option>
                            <option value="10" ${topicLevel == "10" ? 'selected':''}>10</option>
                        </select>
                    </div>
                    <div class="d-flex mt-3">
                        <button type="button" class="cancel-edit btn text-danger mr-2">Cancelar</button>
                        <button type="button" class="save-edit btn text-primary">Salvar</button>
                    </div>
                </div>
            </div>
            `;

            $(topicElement).html(editTopicForm);

            $(`#topic-edit-form-${counterEdit}`).on('click', '.save-edit', function() {
                let title = $(`#title-edit-${counterEdit}`).val();
                let level = $(`#level-edit-${counterEdit}`).val();

                $.ajax({
                method: "PUT",
                url: `/topic/${topicId}/update`,
                data: {
                    title: title,
                    required_level: level
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function() {
                    location.reload(); 
                }
            });
            });

            $(`#topic-edit-form-${counterEdit}`).on('click', '.cancel-edit', function() {
                $(topicElement).html(initialHtml);
            });
        });
    });
</script>


<style scoped>
    .scrollClass {
        width: 300px !important;
    }
</style>
@endsection