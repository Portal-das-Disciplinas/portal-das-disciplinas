@extends('layouts.app')

@section('content')
<div class="container" style="min-height: 65vh;">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="d-flex justify-content-center">
                <h1>Cadastro de níveis de curso</h1>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-course-level">Cadastrar novo</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>

                </thead>
                <tbody>
                    @foreach($courseLevels as $courseLevel)
                    <tr>
                        <td>{{$courseLevel->value}}</td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modal-confirm-delete" 
                                    onclick="setupModalDelete('{{ $courseLevel->id }}','{{ $courseLevel->value }}')">
                                    Apagar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="modal-course-level" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Nível de curso</h3>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="{{ route('course_level.store')}}">
                        @csrf
                        <div class="form-group">
                            <label>Nome do nível</label>
                            <input id="value" name="value" class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label>Prioridade</label>
                            <input id="priority-level" name="priority-level" class="form-control" type="number" min="1">
                        </div>
                        <input id="submit-input" type="submit" hidden>
                    </form>
                </div>
                <div class="modal-footer">
                    <label for="submit-input" href="button" class="btn btn-primary">Cadastrar</label>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-confirm-delete" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Confirmação</h3>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja apagar o nível:</p>
                    <strong id="course-level-value"></strong>
                    <form class="form" method="post" action="">
                        @csrf
                        @method('delete')
                        <input id="course-level-id" name="course-level-id" value="-1" hidden>
                        <input id="submit-delete" type="submit" hidden>

                    </form>
                </div>
                <div class="modal-footer">
                    <label for="submit-delete" href="button" class="btn btn-danger">Apagar</label>
                    <label for="submit-delete" type="button" class="btn btn-secondary" data-dismiss="modal" onclick="setdownModalDelete()">
                        Cancelar
                    </label>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts-bottom')

<script src="{{ asset('js/course_level.js') }}"></script>

@endsection