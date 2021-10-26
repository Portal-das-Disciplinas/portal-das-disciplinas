@extends('layouts.app')

@section('title')
    Cadastrar disciplina - Portal das Disciplinas IMD
@endsection

@section('robots')
    noindex, follow
@endsection

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Cadastro de disciplinas</h2>

        <form action="{{ route("disciplinas.store") }}" method="post">
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
                        <label class="" for="difficulties">
                            Obstáculos
                        </label>
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

                <div class="col-md-6">
                    <div class="form-group">
                        <div class="d-flex justify-content-between">
                            <label class="">Classificações</label>
                            <p data-toggle="tooltip" data-placement="top" title="Deslize os sliders e quantifique em porcentagem o quanto a sua disciplina se encaixa na referida classificação" >Ajuda <i class="far fa-question-circle" ></i></p>
                        </div>
                        <div class="form-group font-weight-normal">
                
                            
                            <!-- #### METODOLOGIA#### ---> 
                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h5>Metodologia</h5>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class=" col-md-2">
                                    <output id="outMetodologia">50</output><span>%<span>
                                </div>
                                <div class="col-md-8">
                                    <input class='w-100' id="classificacao-metodologias" name="classificacao-metodologias" type="range" step="5" value="50" min="0" max="100" oninput="handleInput(outMetodologia, outMetodologiaSecondary, this.value)" >      
                                </div>
                                <div class=" col-md-2 d-flex justify-content-end ">
                                <div>
                                    <output id="outMetodologiaSecondary" >50</output><span>%<span>
                                </div>
                                    
                                </div>
                            </div>
                            <div class="legend row">
                                <div class="d-flex col-md-12 justify-content-between">
                                    <p>Clássica</p>
                                    <p>Ativa</p>
                                </div>
                            </div>

                            <!-- #### DISCUSSAO #### ---> 
                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h5>Discussão</h5>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-2">
                                    <output id="outDiscussao" >50</output><span>%<span>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-range w-100" id="classificacao-discussao" name="classificacao-discussao" type="range" step="5" value="50" min="0" max="100" oninput="handleInput(outDiscussao, outDiscussaoSecondary, this.value)">      
                                </div>
                                <div class="col-md-2 d-flex justify-content-end ">
                                    <output id="outDiscussaoSecondary">50</output><span>%<span>
                                </div>
                            </div>

                            <div class="legend row">
                                <div class="d-flex col-md-12 justify-content-between">
                                    <p>Social</p>
                                    <p>Técnica</p>
                                </div>
                            </div>

                            <!-- #### ABORDAGEM #### ---> 
                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h5>Abordagem</h5>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-2">
                                    <output id="outAbordagem">50</output><span>%<span>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-range w-100" id="classificacao-abordagem" name="classificacao-abordagem" type="range" step="5" value="50" min="0" max="100" oninput="handleInput(outAbordagem, outAbordagemSecondary, this.value)">      
                                </div>
                                <div class="col-md-2  d-flex justify-content-end">
                                    <output id="outAbordagemSecondary">50</output><span>%<span>
                                </div>
                            </div>

                            <div class="legend row">
                                <div class="d-flex col-md-12 justify-content-between">
                                    <p>Prática</p>
                                    <p>Téorica</p>
                                </div>
                            </div>

                            <!-- #### AVALIAÇÃO #### ---> 
                            <div class="row ">
                                <div class=" d-flex justify-content-center col-md-12">
                                    <h5>Avaliação</h5>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="d-flex col-md-2">
                                    <output id="outAvaliacao" >50</output><span>%<span>
                                </div>
                                <div class="col-md-8">
                                    <input class="form-range w-100" id="classificacao-avaliacao" name="classificacao-avaliacao" type="range" step="5" value="50" min="0" max="100" oninput="handleInput(outAvaliacao, outAvaliacaoSecondary, this.value)">      
                                </div>
                                <div class=" col-md-2 d-flex justify-content-end">
                                    <output id="outAvaliacaoSecondary" >50</output><span>%<span>
                                </div>
                            </div>

                            <div class="legend row">
                                <div class="d-flex col-md-12 justify-content-between">
                                    <p>Provas</p>
                                    <p>Atividades</p>
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

                        <div class="d-flex">
                            <label class="" for="media-trailer">
                                Trailer da disciplina
                            </label>
                            <p data-toggle="tooltip" data-placement="top" title="Razões pelas quais esta disciplina pode ser para você." ><i class="far fa-question-circle ml-1" ></i></p>
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
                            <p data-toggle="tooltip" data-placement="top" title="Bate papo entre professores e alunos sobre os principais aspectos da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
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
                            <p data-toggle="tooltip" data-placement="top" title="Pode ser ouvido com o celular travado. Bate papo entre professores e alunos sobre a disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
                        </div>
                        <div class="input-group">
                            <input type="text"
                                   class="form-control {{ $errors->has('media-podcast') ? 'is-invalid' : ''}}"
                                   name="media-podcast"
                                   id="media-podcast"
                                   value="{{old('media-podcast')}}"
                                   aria-describedby="basic-addon3"
                                   placeholder="Link para podcast no driver">
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
                            <p data-toggle="tooltip" data-placement="top" title="Amostra de apostilas, avaliações e outros materiais da disciplina." ><i class="far fa-question-circle ml-1" ></i></p>
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
                </div>
            </div>

            {{-- <div class="form-row mt-3">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="" for="difficulties">
                            Obstáculos
                        </label>
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
                <a href="{{ route('home') }}" class="btn btn-danger btn-sm">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-sm ml-5">Registrar</button>
            </div>
        </form>
    </div>

@endsection
@section('scripts-bottom')
<script>
    function handleInput(outElem, outElemSecondary, value){
        outElem.value = value;
        outElemSecondary.value = 100-value
    }

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })

</script>
@endsection