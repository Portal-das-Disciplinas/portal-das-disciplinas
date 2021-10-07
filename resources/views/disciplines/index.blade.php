@extends('layouts.app')

@section('content')
<div class='wrapper'>


<div class="container" >


    <div class="row">
        <div class="col-12 text-center my-4 title-subject-container">
            <h1 class="title-subject display-title " style='color: #1F2937'>Portal das Disciplinas - IMD/UFRN</h1>
            <div class="row justify-content-center">
                <p class='p-text mt-3  text-center col-md-10  larger-p'>Lorem ipsum dolor sit, Qui sequi iusto sed possimus quos accusamus necessitatibus expedita excepturi, eius mollitia, dolorum odit quas nemo libero saepe architecto repudiandae sint nostrum?<p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="embed-responsive embed-responsive-16by9" style="border-radius:5px">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
</div>
<div class='remove-margin-bottom' style='background-color:#014C8C' >
    <div class="container mt-0">
        <h1 class='text-center text-white pt-5'>Disciplinas Cadastradas</h1>
        <p class='text-center p-text' style='color: #80A6C6'>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Obcaecati possimus eos expedita eum veritatis quasi quae architecto exercitationem molestiae tempore! Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
        {{-- Modificar para apenas o user adm ou professor --}}
    
        @auth
            <div class="row">
                <div class="col-12 col-sm-6 col-lg-3 mt-5">
                    <a name="createDisciplina" class="btn btn-outline-light btn-block"
                       href="{{ route("disciplinas.create") }}" role="button">Cadastrar disciplina</a>
                </div>
            </div>
        @endauth
    
        {{-- <div class="row justify-content-md-center mt-5">
            <div class="col">
            <form action="{{route('search')}}" method="POST">
                @csrf
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Pesquisar..." aria-label="Caixa de pesquisa" aria-describedby="button-addon2" name='search' value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit" id="button-addon2">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> --}}
    
        @isset($disciplines)
            @if ($disciplines->count() == 0)
                <p class="response-search mt-4"> Nenhuma disciplina encontrada </p>
            @else
                <div class="row pb-5" >
                    @foreach ($disciplines as $discipline)
                        <div class="col-12 col-sm-6 col-lg-3 mt-5 ">
                            <div class="card shadow light-border-radius" style="min-height: 400px; max-height: 400px">
                                @if (!is_null($discipline->trailer))
                                    <div class="teacher-video-container">
                                        <div class="embed-responsive embed-responsive-16by9">
                                            <iframe class="embed-responsive-item" src="{{ $discipline->trailer->view_url }}" allowfullscreen></iframe>
                                        </div>
                                    </div>
                                @else
                                    <img src="{{asset('img/IMD_logo.svg')}}" class="card-img-top" alt="..">
                                @endif
    
                                <div class="card-body">
                                    <h5 class="card-title">{{ $discipline->name }}</h5>
                                    <p class="card-text">{{ Str::limit($discipline->synopsis, 70,' (...)') }}</p>
                                    <a href="{{ route('disciplinas.show', $discipline->id) }}" class="btn btn-primary w-100 mt-2">Ver
                                        mais</a>
                                    @auth
                                    <div class='d-flex justify-content-end'>
                                        @if (Auth::user()->canDiscipline($discipline->id))
                                            <div class="dropdown show">
                                                <div class="advanced-options d-flex align-items-center mt-2" data-toggle="dropdown">
                                                    <a class='mr-2'>Opções avançadas</a>
                                                    <i class="fas fa-caret-down"></i>
                                                </div>
                                                <div class="user-dropdown dropdown-menu ">
                                                  
                                                    <form action=" {{route('disciplinas.destroy', $discipline->id)}}" class="dropdown-item"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger w-100 " value="Apagar">Apagar</button>
                                                    </form>
                                                    <form action=" {{route('disciplinas.edit', $discipline->id)}}" class="dropdown-item"
                                                        method="get" class='dropdown-item'>
                                                    @csrf
                                                    @method('UPDATE')
                                                    <button type="submit" class="btn btn-warning w-100 " value="Editar">Editar</button>
                                                    </form>
                                
                                                </div>
                                            </div>
                                      @endif
                                    </div>
                                        
                                    @endauth
                                  
                                            
                                        
                                </div>
                                <div class="card-footer">{{ $discipline->professor->name}}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endisset
    </div>
</div>

</div>

@endsection
