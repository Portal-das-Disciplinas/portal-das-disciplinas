@extends('layouts.app')

@section('title')
    Editar disciplina - Portal das Disciplinas IMD
@endsection

@section('robots')
    noindex, follow
@endsection

@section('content')

<div class="container">
    <h4 class="text-center m-4">Editar disciplina</h4>
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
            <div class="col-md-6 px-0 pr-0">
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
                        <label for="synopsis">
                            Sinopse
                        </label>
                        <textarea
                            class="form-control {{ $errors->has('synopsis') ? 'is-invalid' : ''}}"
                            id="synopsis"
                            name="synopsis"
                            rows="8"
                            placeholder="Explique aqui como funciona a disciplina">{{$discipline->synopsis}}</textarea>
                        @error('synopsis')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="difficulties">
                            Obstáculos
                        </label>
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
                <div class="col-md-6">
                    <div class="form-group font-weight-normal">
                            <label class="font-weight-bold">Classificações</label>
                            
                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h4>Metodologia</h4>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-3">
                                    <p>Clássica </p><output id="outMetodologia" class='ml-3'>{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::METODOLOGIAS)}}</output><span>%<span>
                                </div>
                                <div class="col-md-6">
                                    <input id="classificacao-metodologias" name="classificacao-metodologias" type="range" step="5" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::METODOLOGIAS)}}" min="0" max="100" oninput="handleInput(outMetodologia, outMetodologiaSecondary, this.value)" >      
                                </div>
                                <div class=" d-flex col-md-3">
                                    <p>Ativa</p> <output id="outMetodologiaSecondary" class='ml-3'>{{100-$discipline->getClassificationsValues(\App\Enums\ClassificationID::METODOLOGIAS)}}</output><span>%<span>
                                </div>
                            </div>


                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h4>Discussão</h4>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-3">
                                    <p>Social</p><output id="outDiscussao" class='ml-3'>{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::DISCUSSAO)}}</output><span>%<span>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-range" id="classificacao-discussao" name="classificacao-discussao" type="range" step="5" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::DISCUSSAO)}}" min="0" max="100" oninput="handleInput(outDiscussao, outDiscussaoSecondary, this.value)">      
                                </div>
                                <div class=" d-flex col-md-3">
                                    <p>Técnica</p> <output id="outDiscussaoSecondary" class='ml-3 ''>{{100-$discipline->getClassificationsValues(\App\Enums\ClassificationID::DISCUSSAO)}}</output><span>%<span>
                                </div>
                            </div>


                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h4>Abordagem</h4>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-3">
                                    <p>Prática</p><output id="outAbordagem" class='ml-3 '>{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::ABORDAGEM)}}</output><span>%<span>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-range" id="classificacao-abordagem" name="classificacao-abordagem" type="range" step="5" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::ABORDAGEM)}}" min="0" max="100" oninput="handleInput(outAbordagem, outAbordagemSecondary, this.value)">      
                                </div>
                                <div class=" d-flex col-md-3">
                                    <p>Téorica</p> <output id="outAbordagemSecondary" class='ml-3 '>{{100-$discipline->getClassificationsValues(\App\Enums\ClassificationID::ABORDAGEM)}}</output><span>%<span>
                                </div>
                            </div>

                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h4>Avaliação</h4>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-3">
                                    <p>Por provas</p><output id="outAvaliacao" class='ml-3 '>{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::AVALIACAO)}}</output><span>%<span>
                                </div>
                                <div class="col-md-6">
                                    <input class="form-range" id="classificacao-avaliacao" name="classificacao-avaliacao" type="range" step="5" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::AVALIACAO)}}" min="0" max="100" oninput="handleInput(outAvaliacao, outAvaliacaoSecondary, this.value)">      
                                </div>
                                <div class=" d-flex col-md-3">
                                    <p>Por atividades</p> <output id="outAvaliacaoSecondary" class='ml-3 '>{{100-$discipline->getClassificationsValues(\App\Enums\ClassificationID::AVALIACAO)}}</output><span>%<span>
                                </div>
                            </div>
        
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
                            <label for="media-trailer">
                                Trailer da disciplina
                            </label>
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
                        <label for="media-video">
                            Vídeo
                        </label>
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
                        <label for="media-podcast">
                            Podcast
                        </label>
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
                        <label for="media-material">
                            Materiais
                        </label>
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
                </div>
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

@endsection
@section('scripts-bottom')
<script>
    function handleInput(outElem, outElemSecondary, value){
        outElem.value = value;
        outElemSecondary.value = 100-value
    }
</script>
@endsection