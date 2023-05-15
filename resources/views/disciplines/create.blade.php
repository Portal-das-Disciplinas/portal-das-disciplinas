@extends('layouts.app')

@section('title')
    Cadastrar disciplina - Portal das Disciplinas IMD
@endsection

@section('robots')
    noindex, follow
@endsection

@section('content')
    <div class="container">
        <div class='page-title'>
            <h1>Cadastro de disciplina</h1>
        </div>

        <form action="{{ route('disciplinas.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-10">
                    <label class="" for="name">
                        Nome da disciplina
                    </label>
                    <input type="text"
                           required
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}"
                           id="name"
                           name="name"
                           value="{{old('name')}}"
                           placeholder="Estrutura de dados básica I">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                
            

                <div class="form-group col-md-2">
                    <label class="" for="code">
                        Código
                    </label>
                    <input type="text"
                           required
                           class="form-control {{ $errors->has('code') ? 'is-invalid' : ''}}"
                           id="code"
                           name="code"
                           value="{{old('code')}}"
                           placeholder="IMD0000">
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="col-md-12 px-0">
                
                    <label class="" for="emphasis">
                        Ênfase da disciplina
                    </label>
                    <select name="emphasis" id="emphasis" class='form-control' >
                        <option selected disabled > Selecione uma ênfase</option>
                        @foreach($emphasis as $emphase)
                            <option value="{{ $emphase->id }}" >{{ $emphase->name }}</option>
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
                            <p data-toggle="tooltip" data-placement="top" title="Principais pontos da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <textarea
                            class="form-control {{ $errors->has('synopsis') ? 'is-invalid' : ''}}"
                            id="synopsis"
                            name="synopsis"
                            rows="12"
                            max-rows="12"
                            placeholder="Explique aqui como funciona a disciplina">{{old('synopsis')}}</textarea>
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
                            " ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <textarea
                            class="form-control {{ $errors->has('difficulties') ? 'is-invalid' : ''}}"
                            id="difficulties"
                            name="difficulties"
                            rows="12"
                            max-rows="12"
                            placeholder="Explique aqui como funciona a disciplina">{{old('difficulties')}}</textarea>
                        @error('difficulties')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                </div>


                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <div class="d-flex">
                            <label class="">Classificações</label>
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Deslize os sliders e quantifique em porcentagem o quanto a sua disciplina se encaixa na referida classificação" ><i class="far fa-question-circle ml-1" ></i></p>
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
                                    <input  id="classification-slider" name="classification-{{ $classification->id }}" type="range" min="0" max="100" value="50" step='5' class="classification-slider scrollClass" oninput="handleInput(this.value, this)">
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Razões pelas quais esta disciplina pode ser para você." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-trailer') ? 'is-invalid' : ''}}"
                                   name="media-trailer"
                                   id="media-trailer"
                                   value="{{old('media-trailer')}}"
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Bate papo entre professores e alunos sobre os principais aspectos da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-video') ? 'is-invalid' : ''}}"
                                   name="media-video"
                                   id="media-video"
                                   value="{{old('media-video')}}"
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Pode ser ouvido com o celular travado. Bate papo entre professores e alunos sobre a disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-podcast') ? 'is-invalid' : ''}}"
                                   name="media-podcast"
                                   id="media-podcast"
                                   value="{{old('media-podcast')}}"
                                   aria-describedby="basic-addon3"
                                   placeholder="Link para podcast no Google Drive">
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Amostra de apostilas, avaliações e outros materiais da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-material') ? 'is-invalid' : ''}}"
                                   name="media-material"
                                   id="media-material"
                                   value="{{old('media-material')}}"
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
                                Conhecimentos/Competências Desejados
                            </label>
                            <p data-toggle="tooltip" data-placement="top" title="Conhecimentos e habilidades necessários ou interessantes para que o aluno possa fazer matricula na disciplina
                            " ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <textarea
                            class="form-control {{ $errors->has('acquirements') ? 'is-invalid' : ''}}"
                            id="acquirements"
                            name="acquirements"
                            rows="12"
                            max-rows="12"
                            placeholder="Coloque aqui conhecimentos desejaveis para o aluno cursar a disciplina.">{{old('acquirements')}}</textarea>
                        @error('acquirements')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div> 
                </div>
            </div>
            <div class='page-title'>
                <h3>Cadastro de FAQ</h3>
            </div>
            <div id="faqs">
  
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

            <div class="row d-flex p-2 mt-3 justify-content-center">
                <a id="add-faq" class="btn btn-primary btn-sm mr-5">
                    Adicionar FAQ
                </a>
                <a href="{{ route('home') }}" class="btn btn-danger btn-sm">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-success btn-sm ml-5">Registrar</button>
            </div>
        </form>
    </div>

@endsection

@php
    $classificationsJson = json_encode($classifications);
@endphp

@section('scripts-bottom')
<script>

    let classifications = JSON.parse('{!! $classificationsJson !!}');


    function handleInput(value, element) {
            console.log(value)
            const sliderContainer = element.parentNode
            const leftOutput = sliderContainer.previousElementSibling.querySelector('span')
            const rightOutput = sliderContainer.nextElementSibling.querySelector('span')

            leftOutput.innerText = value
            rightOutput.innerText = 100 - value
        }

    const sliderId = document.querySelector('#classification-slider')
    const leftOutputs = document.querySelectorAll('#left-output-value')
    const rightOutputs = document.querySelectorAll('#right-output-value')

    for(leftOutput of leftOutputs) {
        leftOutput.innerText = sliderId.value
    }

    for(rightOutput of rightOutputs) {
        rightOutput.innerText = sliderId.value
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    var addButton = document.getElementById('add-faq');
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

    // Append form groups to newDiv
    newDiv.appendChild(formGroupTitle);
    newDiv.appendChild(formGroupContent);

    // Append to faqs
    faqs.appendChild(newDiv);
        });
</script>

<style scoped>
    .scrollClass{
        width: 300px !important;
    }
</style>
@endsection
