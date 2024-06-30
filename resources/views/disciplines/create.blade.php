@extends('layouts.app')

@section('title')
Cadastrar disciplina - Portal das Disciplinas
@endsection

@section('robots')
noindex, follow
@endsection

@section('content')
<div class="container">
    <div class='page-title'>
        <h1>Cadastro de disciplina</h1>
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

    <form action="{{ route('disciplinas.store') }}" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-10">
                <label class="" for="name">
                    Nome da disciplina
                </label>
                <input type="text" required class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}" id="name" name="name" value="{{old('name')}}" placeholder="Estrutura de dados básica I">
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group col-md-2">
                <label class="" for="code">
                    Código
                </label>
                <input type="text" required class="form-control {{ $errors->has('code') ? 'is-invalid' : ''}}" id="code" name="code" value="{{old('code')}}" placeholder="IMD0000">
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
                <option selected disabled> Selecione uma ênfase</option>
                @foreach($emphasis as $emphase)
                <option value="{{ $emphase->id }}">{{ $emphase->name }}</option>
                @endforeach
            </select>


        </div>
        <div class="col-md-12 px-0">
            @if (Auth::user()->isAdmin)
            <label for="professor" class="">Professor</label>
            <div class="form-group">
                <select name="professor" id="professor" class="form-control" aria-label="Professor">
                    <option selected>Selecione um professor</option>
                    @foreach ($professors as $professor)
                    <option value="{{$professor->id}}">{{$professor->name}}</option>
                    @endforeach
                </select>
            </div>
            @endif
        </div>
        <div class="form-row mt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <div class="d-flex">
                        <label class="" for="synopsis">
                            Sinopse
                        </label>
                        <p data-toggle="tooltip" data-placement="top" title="Principais pontos da disciplina."><i class="far fa-question-circle ml-1"></i></p>
                    </div>
                    <textarea class="form-control {{ $errors->has('synopsis') ? 'is-invalid' : ''}}" id="synopsis" name="synopsis" rows="12" max-rows="12" placeholder="Explique aqui como funciona a disciplina">{{old('synopsis')}}</textarea>
                    @error('synopsis')
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
                    <textarea class="form-control {{ $errors->has('difficulties') ? 'is-invalid' : ''}}" id="difficulties" name="difficulties" rows="12" max-rows="12" placeholder="Explique aqui como funciona a disciplina">{{old('difficulties')}}</textarea>
                    @error('difficulties')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="card p-2">
                    <h2>Metodologias</h2>
                    <label class="btn btn-success" onclick="openModalAddMethodologies()" data-toggle="modal" data-target="#modal-cadastro-metodologia">Cadastrar ou Adicionar Methodologias</label>
                    <div id="metodologias" class="d-flex flex-column" style="min-height: 50px">{{--Gerado por javascript--}}</div>
                    <input id="selected-professor-methodologies" name="selected-professor-methodologies" hidden>
                </div>

                <div class="form-group">
                    <div class="d-flex flex-column">
                        <label>Conteúdos da disciplina</label>
                        <div class="card p-2 mt-2" style="background-color: #F0F8FF">
                            <div id="area-edit-topics" class="card">
                                <span>Edição da ementa</span>
                                <div id="area-fields-topics">
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="btn-link" onclick="importComponents(event)" style="cursor: pointer;">Importar do SIGAA</small>
                                    <span class="btn btn-primary btn-sm" onclick="addTopicField()">Adicionar campo</span>
                                </div>
                            </div>

                            <div id="area-edit-concepts" class="px-1 mt-2 card">
                                <span>Edição dos conceitos</span>
                                <div id="area-fields-concepts">
                                </div>
                                <div class="d-flex justify-content-end">
                                    <span class="btn btn-primary btn-sm" onclick="addConceptField()">Adicionar campo</span>
                                </div>
                            </div>

                            <div id="area-edit-references" class="px-1 mt-2 card">
                                <span>Edição das referências</span>
                                <div id="area-fields-references">

                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <small class="btn-link" onclick="importReferences(event)" style="cursor: pointer;">Importar do SIGAA</small>
                                    <span class="btn btn-primary btn-sm" onclick="addReferenceField()">Adicionar campo</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <div class="d-flex">
                        <label class="">Classificações</label>
                        <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Deslize os sliders e quantifique em porcentagem o quanto a sua disciplina se encaixa na referida classificação"><i class="far fa-question-circle ml-1"></i></p>
                    </div>

                    <div class="form-group font-weight-normal">
                        @if (count($classifications) > 0)
                        @foreach ($classifications as $classification)

                        <!-- COMPONENTE DO INPUT DE CLASSIFICAO -->
                        <div class="classification-input-component" id='1'>
                            <div>
                                <h3 class='smaller-p text-center'>{{$classification->name}}</h3>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <div>
                                    <div><span id="left-output-value"></span>%</div>
                                </div>
                                <div class="slider-container">
                                    <input id="classification-slider" name="classification-{{ $classification->id }}" type="range" min="0" max="100" value="50" step='5' class="classification-slider scrollClass" oninput="handleInput(this.value, this)">
                                </div>
                                <div>
                                    <div><span id="right-output-value"></span>%</div>
                                </div>
                            </div>
                            <div style="display: flex; justify-content: space-between;" class="classification-subtitiles">
                                <h3 class='smaller-p'>{{ $classification->type_a ?? '' }}</h3>
                                <h3 class='smaller-p'>{{ $classification->type_b ?? '' }}</h3>

                            </div>
                        </div>
                        <!-- FIM DO COMPONENTE DO INPUT DE CLASSIFICAO -->

                        <!-- COMEÇO DA ANTIGA BARRA DE AJUSTE DE PORCENTAGEM -->
                        <!-- <div class="row ">
                                    <div class=" d-flex justify-content-center col-md-12">
                                        <h5>
                                            {{$classification->name}}
                                            @if ($classification->description)
                                                <span data-toggle="tooltip" class="h4" data-placement="top" title=" {{ $classification->description}}"><i class="far fa-question-circle" ></i></span>
                                            @endif
                                        </h5>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="d-flex col-md-2">
                                        <output id="outAvaliacao" value="50" for="outAvaliacao"></output><span>%<span>
                                    </div>
                                    <div class="col-md-8">
                                        <input class="form-range w-100" id="{{ $classification->id }}" name="{{ $classification->name }}" type="range" step="5" value="50" min="0" max="100" oninput="handleInput(outAvaliacao, outAvaliacaoSecondary, this.value)">
                                    </div>
                                    <div class=" col-md-2 d-flex justify-content-end">
                                        <output id="outAvaliacaoSecondary" value="50"></output><span>%<span>
                                    </div>
                                </div>
                                <div class="legend row">
                                    <div class="d-flex col-md-12 justify-content-between">
                                        <p>{{ $classification->type_a ?? '' }}</p>
                                        <p>{{ $classification->type_b ?? '' }}</p>
                                    </div>
                                </div> -->
                        <!-- FIM DA ANTIGA BARRA DE AJUSTE DE PORCENTAGEM -->
                        @endforeach
                        @else
                        <p>Não há classificações cadastradas.</p>
                        @endif



                        {{-- TODO --}}
                        {{-- tentar fazer texto aparecer abaixo do range --}}
                        {{-- <datalist id="tickmarks" style="--list-length: 9;">
                                <option value="0">1</option>
                                <option value="1">2</option>
                                <option value="2">A</option>
                                <option value="3">B</option>
                                <option value="4">C</option>
                                <option value="5">C</option>
                                <option value="6">C</option>
                            </datalist> --}}
                        @error('classificacao')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex">
                        <label class="" for="media-trailer">
                            Trailer da disciplina
                        </label>
                        <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Razões pelas quais esta disciplina pode ser para você."><i class="far fa-question-circle ml-1"></i></p>
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control {{ $errors->has('media-trailer') ? 'is-invalid' : ''}}" name="media-trailer" id="media-trailer" value="{{old('media-trailer')}}" aria-describedby="basic-addon3" placeholder="Link para vídeo no Youtube">
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
                        <input type="text" class="form-control {{ $errors->has('media-video') ? 'is-invalid' : ''}}" name="media-video" id="media-video" value="{{old('media-video')}}" aria-describedby="basic-addon3" placeholder="Link para vídeo no Youtube">
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
                        <input type="text" class="form-control {{ $errors->has('media-podcast') ? 'is-invalid' : ''}}" name="media-podcast" id="media-podcast" value="{{old('media-podcast')}}" aria-describedby="basic-addon3" placeholder="Link para podcast no Google Drive">
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
                        <input type="text" class="form-control {{ $errors->has('media-material') ? 'is-invalid' : ''}}" name="media-material" id="media-material" value="{{old('media-material')}}" aria-describedby="basic-addon3" placeholder="Link para arquivo no Google Drive">
                        @error('media-material')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="form-group">
                    <div class="d-flex">
                        <label class="" for="acquirements">
                            Conhecimentos/Competências Desejados
                        </label>
                        <p data-toggle="tooltip" data-placement="top" title="Conhecimentos e habilidades necessários ou interessantes para que o aluno possa fazer matricula na disciplina
                            "><i class="far fa-question-circle ml-1"></i></p>
                    </div>
                    <textarea class="form-control {{ $errors->has('acquirements') ? 'is-invalid' : ''}}" id="acquirements" name="acquirements" rows="12" max-rows="12" placeholder="Coloque aqui conhecimentos desejaveis para o aluno cursar a disciplina.">{{old('acquirements')}}</textarea>
                    @error('acquirements')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>


        </div>
        <div class='page-title'>
            <h3>Perguntas Frequentes</h3>
        </div>
        <div id="faqs">
        </div>
        <a id="add-faq" class="btn btn-primary">Adicionar FAQ</a>

        <div class='d-flex page-title align-items-center'>
            <h3>Cadastro de créditos</h3>
        </div>
        <input id="participantsList" name="participantsList" type="text" hidden>
        <div id="participants" class='w-100'><!--gerado por javascript -->

        </div>
        <button class="btn btn-primary" onclick="addParticipantField(event)">+ Adicionar participante</button>

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

        {{-- <div class="form-row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="d-flex">
                            <label class="" for="difficulties">
                                Obstáculos
                            </label>
                            <p data-toggle="tooltip" data-placement="top" title="Amostra de apostilas, avaliações e outros materiais da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <textarea style="resize:none"
                                      class="form-control {{ $errors->has('difficulties') ? 'is-invalid' : ''}}"
        id="difficulties"
        name="difficulties"
        rows="4"
        placeholder="Coloque aqui problemas que alunos costumam relatar ao cursar esse componente.">{{old('difficulties')}}</textarea>
        @error('difficulties')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
</div>
</div>
</div>
</div> --}}



<div class="row d-flex mt-3 justify-content-center">
    <div class="col-12 d-flex justify-content-end">
        <button type="submit" class="btn btn-success">Registrar</button>
        <a href="{{ route('home') }}" class="btn btn-danger ml-5">Cancelar</a>
    </div>

</div>

</form>
<div id="modal-cadastro-metodologia" class="modal large fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Cadastro e seleção metodologias</h3>
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
                                <label class="text-secondary" for="nome-nova-metodologia">Nome da
                                    metodologia</label>
                                <input id="nome-nova-metodologia" type='text' class='form-control mb-1'>
                                <label class="text-secondary" for="descricao-nova-metodologia">Descrição da metodologia</label>
                                <textarea id="descricao-nova-metodologia" class='form-control mb-1' rows='6'></textarea>
                                <p><small id="feedback-cadastro-methodology" class="d-none text-success form-text">Metodologia
                                        adicionada</small></p>
                                <button id="btn-create-methodology" class="btn btn-sm btn-outline-primary" onclick="btnCreateMethodology()">Criar</button>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="col-md-12">
                            <hr>
                            <span class="text-primary">Selecione as metodologias abaixo</span>
                        </div>
                        <div class='col-md-12 pt-2' id="methodologiesToChoose" style="border:solid 1px rgba(0,0,0,0.2); border-radius:10px; max-height:400px; overflow:auto">
                            <small class="text-info">carregando metodologias...</small>
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
                <button id="btn-close-modal-add-methodologies" type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btn-add-methodologies" type="button" class="btn btn-sm btn-success" onclick="addSelectedMethodologies()">Adicionar selecionadas</button>
            </div>
        </div>
    </div>
</div>

<div class='modal fade' tabindex='-1' role='dialog' id='methodology-professor-view'>
    <div class='modal-dialog' role='document'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h3 class='modal-title text-primary'>Metodologia</h3>
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
                    <hr class='mt-3'>
                    <div class="flex-group">
                        <label>Nome da disciplina</label>
                        <input id='methodology-name' class='form-control text-primary'>
                    </div>
                    <div id='feedback-delete-remove-methodology' class='alert alert-dismissible d-none mt-2'>
                        <small id='feedback-delete-remove-methodology-message'>Não foi deletar a
                            metodologia</small>
                        <button class='close' onclick="closeAlert('feedback-delete-remove-methodology')">&times</button>
                        </small>
                    </div>

                    <ul id="methodology-description-tabs" class="nav nav-tabs mt-2">
                        <li class="nav-item" style="cursor:pointer">
                            <a id="tab-default-description" class="nav-link active">Descrição padrão</a>
                        </li>
                        <li class="nav-item" style="cursor:pointer">
                            <a id="tab-professor-description" class="nav-link">Sua descrição</a>
                        </li>
                    </ul>
                    <small class='text-secondary'>Descrição da metodologia</small>
                    <textarea id='methodology-description' rows='9' class='text-primary' placeholder="Descreva como é essa metodologia"></textarea>
                    <textarea id='professor-methodology-description' rows='9' class='text-primary d-none' placeholder="Descreva como é esta metodologia"></textarea>
                    <button id='btn-save-methodology' class='btn btn-primary btn-sm my-2' onclick='updateMethodologyAndProfessorMethodology(event)'>Salvar nome e descrição padrão</button>

                    <div id='feedback-methodology' class='d-none alert  mt-2'>
                        <span id='feedback-methodology-message' style='text-align:center'>Erro ao
                            atualizar</span>
                        <button class='close' onclick="closeAlert('feedback-methodology')">&times</button>
                    </div>
                </div>
                <hr>
                <div class='d-flex flex-column'>
                    <small class='text-secondary'>Como o professor aplica a metodologia</small>
                    <textarea id='methodology-use-description' class='text-primary' rows='10' class="text-primary" placeholder="Descreva como você aplica esta metodologia"></textarea>
                    <div id='feedback-professor-methodology' class='d-none alert  mt-2'>
                        <span id='feedback-professor-methodology-message' style='text-align:center'>Erro
                            ao atualizar</span>
                        <button class='close' onclick="closeAlert('feedback-professor-methodology')">&times</button>
                    </div>
                </div>
            </div>
            <div class='modal-footer'>
                <button id="close-modal-save-methodology" type='button' class='btn btn-sm btn-primary' data-dismiss='modal'>Fechar</button>
            </div>
        </div>
    </div>
</div>

</div>

@endsection

@php
$classificationsJson = json_encode($classifications);
@endphp

@section('scripts-bottom')
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

    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })

    var addButton = document.getElementById('add-faq');
    var faqs = document.getElementById('faqs');
    let counter = 0;

    addButton.addEventListener('click', function(event) {
        event.preventDefault();
        counter++;

        // Create new elements
        let newDiv = document.createElement('div');
        newDiv.classList.add('modal-body');

        // Create form group for title
        let formGroupTitle = document.createElement('div');
        formGroupTitle.classList.add('form-group');

        let titleLabel = document.createElement('label');
        titleLabel.setAttribute('for', `title${counter}`);
        titleLabel.classList.add('col-form-label');
        titleLabel.textContent = "Pergunta";

        let titleInput = document.createElement('input');
        titleInput.type = "text";
        titleInput.classList.add('form-control');
        titleInput.id = `title${counter}`;
        titleInput.name = `title[${counter}]`;

        // Append title elements
        formGroupTitle.appendChild(titleLabel);
        formGroupTitle.appendChild(titleInput);

        // Create form group for content
        let formGroupContent = document.createElement('div');
        formGroupContent.classList.add('form-group');

        let contentLabel = document.createElement('label');
        contentLabel.setAttribute('for', `content${counter}`);
        contentLabel.classList.add('col-form-label');
        contentLabel.textContent = "Resposta";

        let contentTextarea = document.createElement('textarea');
        contentTextarea.classList.add('form-control');
        contentTextarea.id = `content${counter}`;
        contentTextarea.name = `content[${counter}]`;

        // Append content elements
        formGroupContent.appendChild(contentLabel);
        formGroupContent.appendChild(contentTextarea);

        // Create delete button
        let deleteButton = document.createElement('button');
        deleteButton.classList.add('btn');
        deleteButton.classList.add('delete-button');
        deleteButton.classList.add('btn-danger');
        deleteButton.textContent = "Deletar FAQ";

        // Add event listener to delete button
        deleteButton.addEventListener('click', function() {
            newDiv.remove();
        });

        // Append form groups, delete button to newDiv
        newDiv.appendChild(formGroupTitle);
        newDiv.appendChild(formGroupContent);
        newDiv.appendChild(deleteButton);

        // Append newDiv to faqs
        faqs.appendChild(newDiv);
    });

    /*Scripts referentes à adição de participantes*/
    let idLinks = 0;
    let participants = [];

    function addParticipantField(event) {
        event.preventDefault();
        let emptyParticipant = {
            name: "",
            role: "",
            email: "",
            index: participants.length,
            links: []
        };
        participants.push(emptyParticipant);
        renderParticipants('#participants');
    }

    function removeParticipantField(event) {
        event.preventDefault();
        index = event.target.id;
        participants = participants.filter(function(participant) {
            return participant.index != index;
        });

        participants.forEach(function(participant, index) {
            participant.index = index;
        });
        renderParticipants("#participants");
    }

    function addLinkField(event) {
        event.preventDefault();

        let link = {
            index: participants[event.target.id].links.length,
            name: "",
            url: ""
        };
        if (participants[event.target.id].links.length >= 3) {
            $('#modalLinksLimit').modal('show');
            return;
        }
        participants[event.target.id].links.push(link);
        renderParticipants("#participants");
    }

    function removeLinkField(event, indexParticipant, linkIndex) {
        event.preventDefault();
        participants[indexParticipant].links = participants[indexParticipant].links.filter(function(link, index) {
            return link.index != linkIndex;
        });
        participants[indexParticipant].links.forEach(function(link, index) {
            link.index = index;
        });

        renderParticipants("#participants");
    }

    function sendParticipantsToFormInput() {
        document.querySelector("#participantsList").value = JSON.stringify(participants);
    }

    function onChangeParticipantName(event) {
        participants[event.target.id].name = event.target.value;
        sendParticipantsToFormInput();

    }

    function onChangeParticipantRole(event) {
        participants[event.target.id].role = event.target.value;
        sendParticipantsToFormInput();
    }

    function onChangeParticipantEmail(event) {
        participants[event.target.id].email = event.target.value;
        sendParticipantsToFormInput();
    }

    function onChangeLinkName(event, participantIndex, linkIndex) {
        participants[participantIndex].links[linkIndex].name = event.target.value;
        sendParticipantsToFormInput();
    }

    function onChangeLinkUrl(event, participantIndex, linkIndex) {
        participants[participantIndex].links[linkIndex].url = event.target.value;
        sendParticipantsToFormInput();
    }

    function renderParticipants(idElement) {
        let html = "";
        participants.forEach(function(participant, index) {
            html +=
                " <div class='d-flex mb-5 flex-column card' style='background-color: #f2f2f2'>" +
                "<div class='p-1 w-100'>" +
                "<div class='form-group'>" +
                "<label for='part1'>Nome</label>" +
                "<input id='" + participant.index + "' class='form-control' type='text' name='participantName[]' placeholder='Nome do Participante' required value='" + participant.name + "' onchange='onChangeParticipantName(event)'>" +
                "</div>" +
                "<div class='form-group'>" +
                "<label>Função</label>" +
                "<input id='" + participant.index + "' class='form-control' type='text' name='participantRole[]' placeholder='Função do Participante' required value='" + participant.role + "' onchange='onChangeParticipantRole(event)'>" +
                "</div>" +
                "<div class='form-group'>" +
                "<label>E-mail</label>" +
                "<input id='" + participant.index + "' class='form-control' type='email' name='participantEmail[]' placeholder='E-mail do Participante'  value='" + participant.email + "' onchange='onChangeParticipantEmail(event)'>" +
                "</div>" +
                "<hr class='hr'>" +
                "<span>LINKS</span>" +
                "<div id='links' class='d-flex flex-column p-1'>";
            participant.links.forEach(function(link, index) {
                html += "<div class='card p-1 mb-1' style='background-color:#e7eaf6'>" +
                    "<div class='form-group w-100'>" +
                    "<input class='form-control' type='text' name='linkName[]' maxlength='20' placeholder='Nome da rede social' required value='" + link.name + "' onchange='onChangeLinkName(event," + participant.index + "," + index + ")'>" +
                    "</div>" +
                    "<div class='form-group'>" +
                    "<input class='form-control mb-0' type='url' name='linkUrl[] p-0' placeholder='http://' required value='" + link.url + "' onchange='onChangeLinkUrl(event," + participant.index + "," + index + ")'>" +
                    "</div>" +
                    "<div class='d-flex mb-2'><small id='" + link.index + "' class='text-danger' style='cursor:pointer;line-height:0.5' onclick='removeLinkField(event," + participant.index + "," + index + ")'>remover link</small></div>" +
                    "</div>";
            });
            html += "</div>" +
                "<a id='" + participant.index + "' class='btn btn-outline-primary btn-sm mt-2' href='#' onclick='addLinkField(event)'>Adicionar link</a>" +
                "</div>" +
                "<div class='d-flex justify-content-end mb-2 mr-1'><button id='" + participant.index + "' class='btn btn-danger btn-sm' onclick='removeParticipantField(event)'>remover participante</button></div>" +

                "</div>";
        });
        document.querySelector(idElement).innerHTML = html;
    }
    renderParticipants('#participants');
</script>
<script src="{{asset('js/subjectContentsEdit.js')}}"></script>
<script>
    let token = "{{csrf_token()}}";
    let professorId = "{{ Auth::user()->professor->id ?? null }}";
    let userIdProfessor = null;
    @if(auth()->user() && auth()->user()->isProfessor)
    userIdProfessor = '{{ Auth::user()->professor->id }}';
    @endif
</script>
<script src="{{ asset('js/disciplines/methodology-create.js') }}"></script>

<script src="{{ asset('js/disciplines/componentesCurriculares.js') }}"></script>

<script>
    let codigo = $('#code').val();

    function importComponents(event) {
        if (!codigo) {
            alert("Por favor, preencha o código da disciplina antes de realizar esta operação");
            return;
        }

        getComponentesCurriculares(codigo).then((data) => {
            if (data) {
                addTopicField(data);
            } else {
                event.target.style.color = "red";
                event.target.innerHTML = "Infelizmente não conseguimos buscar esses dados";
            }
        });
    }

    function importReferences(event) {
        if (!codigo) {
            alert("Por favor, preencha o código da disciplina antes de realizar esta operação");
            return;
        }

        getReferenciasBibliograficas(codigo).then((data) => {
            console.log(data);
            if (data) {
                addReferenceField(data);
            } else {
                event.target.style.color = "red";
                event.target.innerHTML = "Infelizmente não conseguimos buscar esses dados";
            }
        });
    }
</script>


<style scoped>
    .scrollClass {
        width: 300px !important;
    }
</style>
@endsection