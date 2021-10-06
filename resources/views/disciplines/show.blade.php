@extends('layouts.app')

@section('title')
    {{ $discipline->name }} - Portal das Disciplinas IMD
@endsection

@section('description')
    {{ $discipline->name }} - {{ $discipline->code }}, tutorado por {{ $discipline->professor->name }}. Clique para saiber mais.
@endsection

@section('content')
<div class='discipline-banner text-center d-flex align-items-center justify-content-center  text-white'>
    <h1>{{ $discipline->name }} - {{ $discipline->code }}</h1>
</div>

<div class="container" >
    <!-- Botão de cadastro FAQ -->
    @if(isset($can) && $can)
        <div >
            <div class="w-25 my-5">
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-white btn-block text-white"
                        data-toggle="modal" data-target="#faqs-create" style='background-color:#1155CC'>
                    Registrar FAQ
                </button>
            </div>
        </div>
    @endif

    <!-- ROW Da PAGE -->
    <div class="row">
        <!-- main -->
        <div class="main col-md-8">
            <div class='section'>
                
                <!--
                <h4 class='text-center'>Avaliação</h4>
                <div id='classificationBar' class="d-flex" style='background-color:red; height:20px; color:white;'>
                    <div id='classificationBarLeft' class="left-bar" style='background-color:blue; height: 20px; color:white;'>

                    </div>
                </div>
                <div class="labels d-flex justify-content-between">
                    <p id='left-label'></p><p id='right-label'>
                </div>
                -->


                
                <h3 class="mb-3">Trailer & Classificações</h3>
                @if($discipline->has_trailer_media)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item light-border-radius" src="{{ $discipline->trailer->view_url}}" allowfullscreen ></iframe>
                    </div>
                @else
                    <img class="img-fluid light-border-radius" src="{{ asset('img/novideo1.png') }}" alt="Sem trailer">
                @endif
            </div>

            <!-- SINOPSE -->
            <div class="section mt-3">

                <h3 class="mb-3">Sinopse</h3>
                <div>
                    <div>
                        @if($discipline->synopsis=='')
                        <div class="p-text">Não há sinopse.</div>
                        @else
                        <div>{{ $discipline->synopsis}}</div>
                        @endif
                    </div>
                </div>

            </div>

            <!-- VÍDEO -->
            <div class='section'>
                <h3 class="mb-3">Vídeo</h3>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO))
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item light-border-radius" allowfullscreen
                                src="{{ $discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->view_url }}"></iframe>
                    </div>
                @else
                    <img class="img-fluid light-border-radius" src="{{ asset('img/novideo2.png') }}" alt="Sem vídeo">
                @endif
            </div>
            <!-- OBSTACULOS -->
            <div class='section'>
                <h3 class="mb-3">Obstáculos</h3>
                <div>
                    <div>
                        @if($discipline->difficulties=='')
                        <div class=" p-text">Nenhum obstáculo.</div>
                        @else
                        <div>{{ $discipline->difficulties }}</div>
                        @endif
                    </div>
                </div>
            </div>
            <!-- PROFESSOR -->
           



        </div>



        <div class="side col-md-4">
            <!-- classificacoes -->
            <div class='classifications'>
                <!--<h3 class="">Teste</h3>-->

                @foreach ($discipline->classificationsDisciplines as $classification)
                    <div class='row'>
                        <div class="d-flex col-md-12 justify-content-center">
                            <label class="">
                                <h5>{{$classification->classification->name}}</h5>
                            </label>
                        </div>
                    
                    </div>
                    <div class="row d-flex align-items-center">
                        
                        
                        <div class="d-flex col-md-12">
                            <span class='d-flex justify-content-start' style='width:15%'><b>{{(number_format(($classification->value),1))}}%</b></span>
                            <div class="progress " class='col-md-8' style="height: 20px; border-radius: 100px ; border: 2px solid #1155CC; padding: 2px; width:70%">


                                <div id="{{$classification->classification_id}}"
                                     class="progress-bar" role="progressbar"

                                     style="width: {{($classification->value)}}% ; background-color:#1155CC; border-radius: 100px 0 0 100px"
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="20"> 
                                </div>

                                <div id="{{$classification->classification_id}}"
                                    class="progress-bar" role="progressbar"

                                    style="width: {{(100-$classification->value)}}% ; background-color:#4CB944; border-radius: 0 100px 100px 0"
                                    aria-valuenow="0" aria-valuemin="0" aria-valuemax="20"> 
                               </div>


                            </div>
                            <span class='d-flex justify-content-end' style='width:15%'><b>{{(100-number_format(($classification->value),1))}}%</b></span>

                        </div>

                    </div>
                    
                    <div class="row ">
                        <div class="col-md-12 d-flex justify-content-between">
                            @foreach ($classificationsTypes[$classification->classification_id] as $type)
                                <span>{{$type}}</span>
                            @endforeach
                        </div>
                    </div>
                @endforeach


            </div>
            <hr>
             <!-- PODCAST -->
            <div>
                <h3 class=" mt-4 mb-2">Podcast</h3>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST))
                    <audio class="w-100" controls="controls">
                        <source src="{{ $discipline->getMediasByType(\App\Enums\MediaType::PODCAST)->first()->view_url}}" type="audio/mp3" />
                        seu navegador não suporta HTML5
                    </audio>
                @else
                    <img class="img-fluid light-border-radius" src="{{ asset('img/nopodcast.png') }}" alt="Sem podcast">
                @endif
            </div>
            <hr>


             <!-- MATERIAIS -->
            
            <div>
                <h3 class=" mt-4 mb-2">Materiais</h3>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS))
                    <div class="align-center">
                        <p>Clique no botão abaixo e faça o download dos materiais da disciplina.</p>
                        <a href="{{ $discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->view_url}}"
                           class="text">
                            <!-- <i class="fas fa-file-download fa-9x materiais-on"></i> -->
                            <button class="btn btn-primary mt-3" style='width:50%'> <i class="fas fa-file-download fa-lg mr-1"></i> Download</button>
                        </a>
                        <br/>
                    </div>
                @else
                <div class="d-flex align-items-center">
                    <i class="fas fa-sad-tear fa-7x mr-3" ></i>
                    <strong>Sem materiais disponiveis...</strong>
                </div>

                @endif
            </div>
            <hr>




        </div>
    </div>
    


</div>

</div>
<!-- FAQ -->
<div class=" pt-4 pb-5" style=' margin-bottom: -3rem;'>
@if($discipline->faqs->count())
<div class="container">
    <h2 class="container-fluid  text-center mt-5">Perguntas Frequentes</h2>
    <div class="row mt-3" id="faqs">
        @foreach($discipline->faqs as $faq)
            <div class="w-100 card mb-3 text-dark "style='border:1px solid #014C8C;'>
                <div class="card-header" id="faq-header-{{$faq->id}}" data-toggle="collapse" data-target="#faq-content-{{$faq->id}}" >
                    <h5 class="mb-0 d-flex justify-content-between" >
                        <button class="btn btn-link collapsed mr-auto" data-toggle="collapse"
                                data-target="#faq-content-{{$faq->id}}"
                                aria-expanded="true" aria-controls="faq-header-{{$faq->id}}"  >
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
                                data-target="#faq-content-{{$faq->id}}"
                                aria-expanded="true" aria-controls="faq-header-{{$faq->id}}">
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

</div>
<div class="container">
    <div class='section mb-5'>
        <h3 class="mb-3">Professor</h3>
        <div class="">
            <div class="d-flex align-items-center">
                
                <i class="fas fa-user fa-8x mr-4" ></i>
                <div class="wrapper-teacher-info">
                    <div class="text-justify px-lg-3"> <strong>{{ $discipline->professor->name }}</strong> </div>
                    <div class="text-justify px-lg-3"> <strong>Email: </strong>{{ $discipline->professor->public_email }} </div>
                    <div class="text-justify px-lg-3"> <a href="{{$discipline->professor->public_link}}" target="_blank"> Link publico</a> </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>



@endsection
@section('scripts-bottom')
<script>

</script>

@endsection
