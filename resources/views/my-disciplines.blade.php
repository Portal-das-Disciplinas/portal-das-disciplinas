@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12 col-sm-6 col-lg-3 mt-5">
            <a name="createDisciplina" class="btn btn-outline-light btn-block" href="{{ route("disciplinas.create") }}" role="button">Cadastrar disciplina</a>
        </div>
    </div>

    @isset($disciplines)
        @if($disciplines->count() == 0)
            <p class="response-search"> Nenhuma disciplina encontrada </p>
        @else
            <div class="row">
                @foreach ($disciplines as $discipline)
                    <div class="col-12 col-sm-6 col-lg-3 mt-5">
                        <div class="card shadow">
                            {{-- <img src="{{asset('img/teste1.jpg')}}" class="card-img-top" alt=".." > --}}

                            <div class="teacher-video-container">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/cNxNWBrMtig"
                                        allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $discipline->name }}</h5>
                                <p class="card-text">{{ Str::limit($discipline->description, 100,' (...)') }}</p>
                            </div>
                            <div class="card-footer">{{ Str::words( $discipline->nameUser , 2, '' ) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endisset


@endsection
