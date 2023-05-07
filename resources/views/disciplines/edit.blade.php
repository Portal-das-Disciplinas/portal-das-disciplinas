@extends('layouts.app')

@section('title')
    Editar disciplina - Portal das Disciplinas IMD
@endsection

@section('robots')
    noindex, follow
@endsection

@section('content')

<div class="container">
    <div class='page-title'>
        <h1>Editar disciplina</h1>
    </div>

    <h4 class="text-center m-4"></h4>
    <div class=" font-weight-bold">
        <form action="{{ route("disciplinas.update" , $discipline->id)}}" method="post">
            @csrf
            @method('PUT')
            <div class="form-row">
                <div class="form-group col-md-10">
                    <label for="name">
                        Nome da disciplina
                    </label>
                    <input type="text"
                           required
                           class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}"
                           id="name"
                           name="name"
                           value="{{$discipline->name}}"
                           placeholder="Estrutura de dados básica I">
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
    
                <div class="form-group col-md-2">
                    <label for="code">
                        Código
                    </label>
                    <input type="text"
                           required
                           class="form-control {{ $errors->has('code') ? 'is-invalid' : ''}}"
                           id="code"
                           name="code"
                           value="{{$discipline->code}}"
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
                            <p data-toggle="tooltip" data-placement="top" title="Principais pontos da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        
                        <textarea
                            class="form-control {{ $errors->has('description') ? 'is-invalid' : ''}}"
                            id="description"
                            name="description"
                            rows="8"
                            placeholder="Explique aqui como funciona a disciplina">{{$discipline->description}}</textarea>
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
                            " ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <textarea
                                      class="form-control {{ $errors->has('difficulties') ? 'is-invalid' : ''}}"
                                      id="difficulties"
                                      name="difficulties"
                                      rows="8"
                                      placeholder="Coloque aqui problemas que alunos costumam relatar ao cursar esse componente.">{{$discipline->difficulties}}</textarea>
                            @error('difficulties')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                </div>
                {{-- TODO
                Card de midias com "x" para excluir --}}
                <div class="col-md-6 col-sm-12">
                    <div class="form-group font-weight-normal">
                            <div class="d-flex justify-content-between">
                                <label class="font-weight-bold">Classificações</label> 
                                <p data-toggle="tooltip" data-placement="top" title="Deslize os sliders e quantifique em porcentagem o quanto a sua disciplina se encaixa na referida classificação" >Ajuda <i class="far fa-question-circle" ></i></p>
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
                                        <input id="classification-slider" name="classification-{{ $classification->id }}"  type="range" min="0" max="100" value="{{$discipline->getClassificationsValues($classification->id)}}" step='5' class="classification-slider scrollClass classification-{{$classification->id}}" oninput="handleInput(this.value, this)">
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
                                <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Razões pelas quais esta disciplina pode ser para você." ><i class="far fa-question-circle ml-1" ></i></p>
                            </div>
                            <div class="input-group">
                                <input type="text"
                                    class="form-control {{ $errors->has('media-trailer') ? 'is-invalid' : ''}}"
                                    name="media-trailer"
                                    id="media-trailer"
                                    @if ($discipline->has_trailer_media)
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Bate papo entre professores e alunos sobre os principais aspectos da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-video') ? 'is-invalid' : ''}}"
                                   name="media-video"
                                   id="media-video"
                                   @if ($discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO))
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Pode ser ouvido com o celular travado. Bate papo entre professores e alunos sobre a disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-podcast') ? 'is-invalid' : ''}}"
                                   name="media-podcast"
                                   id="media-podcast"
                                   @if ($discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST))
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
                            <p class='tooltip-text' data-toggle="tooltip" data-placement="top" title="Amostra de apostilas, avaliações e outros materiais da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-material') ? 'is-invalid' : ''}}"
                                   name="media-material"
                                   id="media-material"
                                   @if ($discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS))
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
                            " ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <textarea
                                      class="form-control {{ $errors->has('acquirements') ? 'is-invalid' : ''}}"
                                      id="acquirements"
                                      name="acquirements"
                                      rows="8"
                                      placeholder="Coloque aqui conhecimentos desejaveis para o aluno cursar a disciplina.">{{$discipline->acquirements}}</textarea>
                            @error('acquirements')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> 
                </div>
                <div class="w-25 my-5">
            <!-- Button trigger modal -->

        </div>
        <button type="button" class="btn btn-outline-white btn-block text-white" data-toggle="modal"
                data-target="#faqs-create" style='background-color:#1155CC'>
                Registrar FAQ
            </button>
            </div>
    
            <div class="row d-flex p-2 mt-3 justify-content-center">
                <a href="{{ route('home') }}" class="btn btn-danger btn-sm">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-sm ml-5">Editar</button>
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
<script>
    let classifications = JSON.parse('{!! $classificationsJson !!}');

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

    for(leftOutput of leftOutputs) {
        leftOutput.innerText = sliderId.value
    }

    for(rightOutput of rightOutputs) {
        rightOutput.innerText = sliderId.value
    }

    /* tooltip initialize */
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

    
</script>

<style scoped>
    .scrollClass{
        width: 300px !important;
    }
</style>
@endsection