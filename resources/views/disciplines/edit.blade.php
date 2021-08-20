@extends('layouts.app')

@section('title')
    Editar disciplina - Portal das Disciplinas IMD
@endsection

@section('robots')
    noindex, follow
@endsection

@section('content')
    <h4 class="text-white">Editar disciplina</h4>
    <form action="{{ route("disciplinas.update" , $discipline->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="form-row">
            <div class="form-group col-md-10">
                <label class="text-white" for="name">
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
                <label class="text-white" for="code">
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
        <div class="col-md-6 px-0">
            <label for="professor" class="text-white">Professor</label>
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
                    <label class="text-white" for="synopsis">
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

                <div class="form-group mt-3 ml-2">
                    <h3 class="text-white">Classificações</h3>
                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Metodologias Clássicas
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input id="classificacao-metodologias-classicas" name="classificacao-metodologias-classicas" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::METODOLOGIAS_CLASSICAS)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Metodologias Ativas
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range" id="classificacao-metodologias-ativas" name="classificacao-metodologias-ativas" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::METODOLOGIAS_ATIVAS)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Discussão Social
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range" id="classificacao-discussao-social" name="classificacao-discussao-social" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::DISCUSSAO_SOCIAL)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Discussão Técnica
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range" id="classificacao-discussao-tecnica" name="classificacao-discussao-tecnica" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::DISCUSSAO_TECNICA)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Abordagem Teórica
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range" id="classificacao-abordagem-teorica" name="classificacao-abordagem-teorica" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::ABORDAGEM_TEORICA)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Abordagem Prática
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range" id="classificacao-abordagem-pratica" name="classificacao-abordagem-pratica" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::ABORDAGEM_PRATICA)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Avaliação por Provas
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range" id="classificacao-av-provas" name="classificacao-av-provas" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::AVALIACAO_PROVAS)}}" list="tickmarks">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-5 mt-1">
                            <label class="text-white">
                                Avaliação por Atividades
                            </label>
                        </div>
                        <div class="col-md-6">
                            <div>
                                <input class="form-range form-group" id="classificacao-av-atividades" name="classificacao-av-atividades" type="range" step="1" min="0" max="6" value="{{$discipline->getClassificationsValues(\App\Enums\ClassificationID::AVALIACAO_ATIVIDADES)}}" list="tickmarks">
                            </div>
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
            </div>
            {{-- TODO
            Card de midias com "x" para excluir --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="text-white" for="media-trailer">
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
                    <label class="text-white" for="media-video">
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
                    <label class="text-white" for="media-podcast">
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
                    <label class="text-white" for="media-material">
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

        <div class="form-row mt-3">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="text-white" for="difficulties">
                        Obstáculos
                    </label>
                    <div class="input-group">
                        <textarea style="resize:none"
                                  class="form-control {{ $errors->has('difficulties') ? 'is-invalid' : ''}}"
                                  id="difficulties"
                                  name="difficulties"
                                  rows="4"
                                  placeholder="Coloque aqui problemas que alunos costumam relatar ao cursar esse componente.">{{$discipline->difficulties}}</textarea>
                        @error('difficulties')
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

@endsection
