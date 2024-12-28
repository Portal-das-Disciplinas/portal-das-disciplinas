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
            <button class="btn btn-primary" data-toggle='modal' data-target='#modal-course'>Cadastrar novo curso</button>
            @if(Auth::user() && Auth::user()->is_admin)
            <a class="btn btn-outline-primary" href="{{ route('course_level.index') }}">Cadastrar nível de curso</a>
            @endif
        </div>
    </div>
    @if(session('success_message'))
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p>{{session('success_message')}}</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                <p>{{$error}}</p>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif
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
                    <form method="post" class="form" action="{{ route('course.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nome do curso</label>
                            <input type="text" name="course-name" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Unidade</label>
                            <select type="text" name="unit-id" class="form-control">
                                @foreach($institutionalUnits as $institutionalUnit)
                                <option value="{{ $institutionalUnit->id }}">{{$institutionalUnit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Nivel do Curso</label>
                            <select class="form-control" name="course-level-id">
                                @foreach($courseLevels as $courseLevel)
                                <option value="{{ $courseLevel->id }}">{{$courseLevel->value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <input id="submit-course-form" type="submit" hidden>


                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <label for="submit-course-form" class="btn btn-primary">Cadastrar</label>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection