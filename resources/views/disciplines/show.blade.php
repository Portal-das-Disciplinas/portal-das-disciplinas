@extends('layouts.app')

@section('title')
{{ $discipline->name }} - Portal das Disciplinas IMD
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/discipline.css')}}">
@endsection

@section('description')
{{ $discipline->name }} - {{ $discipline->code }}, tutorado por {{ $discipline->professor->name }}. Clique para saiber
mais.
@endsection

@section('content')
<div class='banner text-center d-flex flex-column align-items-center justify-content-center  text-white'>
    <h1 class='display-title'>{{ $discipline->name }} - {{ $discipline->code }}</h1>
    <h3>{{ $discipline->emphasis}}</h3>
</div>

<div class="container mt-4">
    <!-- Botão de cadastro FAQ -->

    @auth

    @if (Auth::user()->canDiscipline($discipline->id))
    <h3 class="mt-3">Menu do professor</h3>
    @if(isset($can) && $can)
    <button type="button" class="btn btn-outline-white text-white w-25 mt-2" data-toggle="modal"
                data-target="#faqs-create" style='background-color:#1155CC'>
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
        <div class="main col-md-8">
            <div class='section'>
                <h1 class="mb-3">Trailer</h1>
                @if($discipline->has_trailer_media && $discipline->trailer->view_url != '')
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe style='border-radius: 6px;' class="embed-responsive-item"
                        src="{{ $discipline->trailer->view_url}}" allowfullscreen></iframe>
                </div>
                @else
                <img style='border-radius: 6px;' class="img-fluid" src="{{ asset('img/novideo1.png') }}"
                    alt="Sem trailer">
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

            </div>



            <!-- VÍDEO -->
            <div class='section'>
                <h1 class="mb-3">Vídeo</h1>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO) &&
                $discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->view_url != '')
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe style='border-radius: 6px;' class="embed-responsive-item " allowfullscreen
                        src="{{ $discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->view_url }}"></iframe>
                </div>
                @else
                <img style='border-radius: 6px;' class="img-fluid" src="{{ asset('img/novideo2.png') }}"
                    alt="Sem vídeo">
                @endif
            </div>
            <!-- OBSTACULOS -->
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
            <!-- PROFESSOR -->
        </div>

        <div class="side col-md-4">
            <div class='classifications'>
                <h1 class="">Classificações</h1>
                @if (count($classifications)>0)
                @foreach ( $classifications as $classification)
                <div class='row mb-0'>
                    <div class="d-flex col-md-12 justify-content-center">
                        <label class="">
                            <div class="d-flex">
                                <h3 style='margin-bottom: 0;' class='smaller-p'>
                                    {{$classification->name ?? ''}}

                                    @if ($classification->description)
                                    <span data-toggle="tooltip" data-placement="top"
                                        title=" {{ $classification->description}}"><i
                                            class="far fa-question-circle"></i></span>
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
                        <div class="progress " class='col-md-8'
                            style="height: 20px; border-radius: 100px ; border: 2px solid black; padding: 2px; width:70%">
                            <div id="{{$classification->classification_id}}"
                                class="classification-color-left progress-bar" role="progressbar"
                                style="width: {{ $discipline->getClassificationsValues($classification->id) }}%; border-radius: 100px 0 0 100px"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="20">
                            </div>

                            <div id="{{$classification->classification_id}}"
                                class="classification-color-right progress-bar" role="progressbar"
                                style="width: {{(100-$discipline->getClassificationsValues($classification->id))}}% ; border-radius: 0 100px 100px 0"
                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="20">
                            </div>
                        </div>
                        <span class='d-flex justify-content-end'
                            style='width:15%'><b>{{(100-number_format(($discipline->getClassificationsValues($classification->id)),1))}}%</b></span>
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
                    <source src="{{ $discipline->getMediasByType(\App\Enums\MediaType::PODCAST)->first()->view_url}}"
                        type="audio/mp3" />
                    seu navegador não suporta HTML5
                </audio>
                @else
                <img class="img-fluid light-border-radius" src="{{asset('img/nopodcast.png') }}" alt="Sem podcast">
                @endif
            </div>
            <hr>


            <!-- MATERIAIS -->

            <div>
                <h1 class=" mt-4 mb-2 py-3">Materiais</h1>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS) &&
                $discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->view_url != '')
                <div class="align-center">

                    <a href="{{ $discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->view_url}}"
                        class="text">
                        <!-- <i class="fas fa-file-download fa-9x materiais-on"></i> -->
                        <button class="btn large-secondary-button my-3 w-100"> <i
                                class="fas fa-file-download fa-lg mr-1"></i> Download</button>
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
            <div class='section'>
                <h1 class="mb-3">Conhecimentos/Competências Desejados</h1>
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



</div>

</div>
<!-- FAQ -->

@if($discipline->faqs->count())
<div class="container">
    <h1 class="container-fluid  text-center mt-5">Perguntas Frequentes</h1>
    <div class="row mt-3" id="faqs">
        @foreach($discipline->faqs as $faq)
        <div class="w-100 card mb-3 text-dark " style='border:1px solid #014C8C;'>
            <div class="card-header" id="faq-header-{{$faq->id}}" data-toggle="collapse"
                data-target="#faq-content-{{$faq->id}}">
                <h5 class="mb-0 d-flex justify-content-between">
                    <button class="btn btn-link collapsed mr-auto" data-toggle="collapse"
                        data-target="#faq-content-{{$faq->id}}" aria-expanded="true"
                        aria-controls="faq-header-{{$faq->id}}">
                        {!! $faq->title !!}
                    </button>

                    @if(isset($can) && $can)
                    <form action=" {{route('disciplinas.faqs.destroy', [$discipline->id, $faq->id])}}"
                        class="d-inline float-right" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mt-2" value="Apagar">Apagar</button>
                    </form>
                    @endif

                    <button class="btn btn-link collapsed ml-2" data-toggle="collapse"
                        data-target="#faq-content-{{$faq->id}}" aria-expanded="true"
                        aria-controls="faq-header-{{$faq->id}}">
                        <i class="fas fa-caret-down"></i>
                    </button>
                </h5>
            </div>

            <div id="faq-content-{{$faq->id}}" class="collapse" aria-labelledby="faq-header-{{$faq->id}}"
                data-parent="#faqs">
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
<div class=" pt-4 pb-5" style=' margin-bottom: -3rem;'>

    <div class="container col-md-5">
        <div class="section">
            <h1 class="container-fluid  text-center mt-5">Faça uma pergunta!</h1>
            <!-- É necessário autenticaro  email do professor anteriormente -->

            <form id="formDuvida" action="https://formsubmit.co/" method="POST">
                <!-- COLOQUE NO INPUT ABAIXO O EMAIL PARA ENVIAR UMA CÓPIA (EMAIL DE EUGÊNIO) -->
                <input type="hidden" name="_cc" value="eugenio@imd.ufrn.br" />
                <input type="hidden" name="_cc" value="{{ $discipline->professor->public_email }}" />
                <div class="form-group">
                    <label for="studentEmail">Email</label>
                    <input type="email" id='studentEmail' name='Email do estudante' class="form-control" placeholder="Digite aqui o seu email..." required>
                </div>
                <div class="form-group">
                    <label for="studentQuestion">Título</label>
                    <input type='text' id='studentQuestion' name='Título da pergunta' class="form-control" placeholder="Sua pergunta aqui...">
                </div>

                <div class="form-group">
                    <label for="studentQuestionDetails">Descrição da pergunta</label>
                    <textarea class="form-control" name='Detalhes' rows="3" placeholder="Forneça mais detalhes da sua pergunta..."></textarea>
                </div>
                <input type="hidden" name="_next" value='http://127.0.0.1:8000/disciplinas/{{$discipline->id}}'>
                <button class='blue-btn btn w-100' type="submit">Enviar pergunta</button>
            </form>
        </div>
    </div>

</div>
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



@endsection
@section('scripts-bottom')
<script>
    document.getElementById("formDuvida").addEventListener("submit", function(event) {
    var studentEmail = document.getElementById("studentEmail").value;
    var formAction = "https://formsubmit.co/" + encodeURIComponent(studentEmail);
    this.action = formAction;
  });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
</script>

@endsection