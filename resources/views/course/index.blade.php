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
    @auth
    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>
                    <th>Nome</th>
                    <th>Nivel</th>
                    <th>Unidade</th>
                </thead>
                <tbody>
                    @foreach($courses as $course)
                    <tr>
                        <td>{{$course->name}}</td>
                        <td>{{$course->courseLevel->value}}</td>
                        <td>{{$course->institutionalUnit->name}}</td>
                        <td><button class="btn btn-danger">Apagar</button></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endauth

    <div id="modal-course" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLabel">Novo curso</h3>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection