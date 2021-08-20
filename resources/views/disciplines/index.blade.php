@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 text-center my-4 title-subject-container">
            <h1 class="title-subject">Portal das Disciplinas - IMD/UFRN</h1>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="embed-responsive embed-responsive-16by9">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/qG4ATq0qJlE" allowfullscreen></iframe>
            </div>
        </div>
    </div>

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
            <div class="row">
                @foreach ($disciplines as $discipline)
                    <div class="col-12 col-sm-6 col-lg-3 mt-5">
                        <div class="card shadow">
                            @if (!is_null($discipline->trailer))
                                <div class="teacher-video-container">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item" src="{{ $discipline->trailer->url }}" allowfullscreen></iframe>
                                    </div>
                                </div>
                            @else
                                <img src="{{asset('img/IMD_logo.svg')}}" class="card-img-top" alt="..">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $discipline->name }}</h5>
                                <p class="card-text">{{ Str::limit($discipline->synopsis, 70,' (...)') }}</p>
                                <a href="{{ route('disciplinas.show', $discipline->id) }}" class="btn btn-primary mt-2">Ver
                                    mais</a>

                            @auth
                                 @if (Auth::user()->canDiscipline($discipline->id))
                                    <form action=" {{route('disciplinas.destroy', $discipline->id)}}" class="d-inline"
                                          method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger mt-2" value="Apagar">Apagar</button>
                                    </form>
                                    <form action=" {{route('disciplinas.edit', $discipline->id)}}" class="d-inline"
                                        method="get">
                                      @csrf
                                      @method('UPDATE')
                                      <button type="submit" class="btn btn-warning mt-2" value="Editar">Editar</button>
                                    </form>
                                @endif
                            @endauth
                            </div>
                            <div class="card-footer">{{ $discipline->professor->name}}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endisset
@endsection
