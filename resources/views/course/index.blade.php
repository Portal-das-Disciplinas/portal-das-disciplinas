@extends('layouts.app')

@section('content')
<div class="container" style="min-height: 65vh;">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <h1>Cadastro do Cursos</h1>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-primary">Cadastrar novo curso</button>
            @if(Auth::user() && Auth::user()->is_admin)
            <a class="btn btn-outline-primary" href="{{ route('course_level.index') }}">Cadastrar nível de curso</a>
            @endif
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-6">

        </div>
    </div>


</div>

@endsection