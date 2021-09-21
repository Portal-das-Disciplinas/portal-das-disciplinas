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
                <h3 class="mb-3">Trailer & Classificações</h3>
                @if($discipline->has_trailer_media)
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item light-border-radius" src="{{ $discipline->trailer->url}}" allowfullscreen ></iframe>
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
                        <div class=" ln-30 p-text"> {{ $discipline->synopsis }} </div>
                    </div>
                </div>

            </div>

            <!-- VÍDEO -->
            <div class='section'>
                <h3 class="mb-3">Vídeo</h3>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::VIDEO))
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item light-border-radius" allowfullscreen
                                src="{{ $discipline->getMediasByType(\App\Enums\MediaType::VIDEO)->first()->url }}"></iframe>
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
                        <div class=" p-text">{{ $discipline->difficulties }}</div>
                    </div>
                </div>
            </div>
            <!-- PROFESSOR -->
           



        </div>



        <div class="side col-md-4">
            <!-- classificacoes -->
            <div class='classifications'>
                <!--<h3 class="">Teste</h3>-->

                @foreach ($discipline->classificationsDisciplines as $classificationDiscipline)
                    <div class="row d-flex align-items-center">
                        <div class="col-md-5 mt-1">
                            <label class="">
                                {{$classificationDiscipline->classification->name}}
                            </label>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="progress" style="height: 20px; border-radius: 100px ; border: 2px solid #1155CC; padding: 2px;">

                                <div id="{{$classificationDiscipline->classification_id}}"
                                     class="progress-bar" role="progressbar"

                                     style="width: {{($classificationDiscipline->value/6)*100}}% ; background-color:#1155CC; border-radius: 100px"
                                     aria-valuenow="0" aria-valuemin="0" aria-valuemax="20"><!--{{(number_format(($classificationDiscipline->value/6*100),1))}}%--></div>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
            <hr>
             <!-- PODCAST -->
            <div>
                <h3 class=" mt-4 mb-2">Podcast</h3>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::PODCAST))
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item light-border-radius" allowfullscreen
                                src="{{ $discipline->getMediasByType(\App\Enums\MediaType::PODCAST)->first()->url}}"></iframe>
                    </div>
                @else
                    <img class="img-fluid light-border-radius" src="{{ asset('img/nopodcast.png') }}" alt="Sem podcast">
                @endif
                
            </div>
            <hr>


             <!-- MATERIAIS -->
            
            <div>
                <h3 class=" mt-4 mb-2">Materiais</h3>
                @if($discipline->hasMediaOfType(\App\Enums\MediaType::MATERIAIS))
                    <div class="d-flex align-center">
                        <a href="{{ $discipline->getMediasByType(\App\Enums\MediaType::MATERIAIS)->first()->url}}"
                           class="text">
                            <!-- <i class="fas fa-file-download fa-9x materiais-on"></i> -->
                            <button class="btn btn-primary">Download dos materiais<img src="{{ asset('img/Download1.png') }}" alt="Download dos materiais" class="ml-2"></button>
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
<!-- FAQ -->
<div class=" pt-4 pb-5" style=' margin-bottom: -3rem;'>
@if($discipline->faqs->count())
<div class="container">
    <h2 class="container-fluid  text-center mt-5">Perguntas Frequentes</h2>
    <div class="row mt-3" id="faqs">
        @foreach($discipline->faqs as $faq)
            <div class="w-100 card mb-3 text-dark "style='border:2px solid #014C8C;'>
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
                    <div class="text-justify px-lg-3"> <a href="https://www.google.com/"  class="">Link pessoal</a></div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts-bottom')
<script>
    let classificationsPairs = []
    let classificationsDisciplines = {prop: @json($discipline->classificationsDisciplines)}
    for (let i = 0; i < classificationsDisciplines.prop.length; i+=2) {
        classificationsPairs.push(classificationsDisciplines.prop.slice(i,i+2)) 
    }
    console.log(classificationsPairs)
</script>

@endsection
