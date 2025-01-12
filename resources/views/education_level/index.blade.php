@extends('layouts.app')

@section('content')
<div class="container" style="min-height: 65vh;">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="d-flex justify-content-center">
                <h1>Cadastro de níveis de ensino</h1>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-education-level">Cadastrar novo</button>
        </div>
    </div>
    @if(session('success_message'))
    <div class="row mt-1">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <span>{{session('success_message')}}</span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="row mt-1">
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                <span>{{$error}}</span>
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    </div>
    @endif



    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>

                </thead>
                <tbody>
                    @foreach($educationLevels as $educationLevel)
                    <tr>
                        <td>{{$educationLevel->value}}</td>
                        <td>
                            <button class="btn btn-warning" onclick="onClickUpdate('{{$educationLevel->id}}','{{$educationLevel->value}}','{{$educationLevel->priority_level}}')">Atualizar</button>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modal-confirm-delete" 
                                    onclick="setupModalDelete('{{ $educationLevel->id }}','{{ $educationLevel->value }}')">
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

    <div id="modal-education-level" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Nível de ensino</h3>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="{{ route('education_level.store')}}">
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

    <div id="modal-education-level-edit" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Nível de ensino</h3>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Nome do nível</label>
                            <input id="value" name="value" class="form-control" type="text">
                        </div>
                        <div class="form-group">
                            <label>Prioridade</label>
                            <input id="priority-level" name="priority-level" class="form-control" type="number" min="1">
                        </div>
                        <input id="submit-update" type="submit" hidden>
                    </form>
                </div>
                <div class="modal-footer">
                    <label for="submit-update" href="button" class="btn btn-primary">Atualizar</label>
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
                    <strong id="education-level-value"></strong>
                    <form class="form" method="post" action="">
                        @csrf
                        @method('delete')
                        <input id="education-level-id" name="education-level-id" value="-1" hidden>
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

<script src="{{ asset('js/education_level/index.js') }}"></script>

@endsection