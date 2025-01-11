@extends('layouts.app');

@section('content')
<div class="container" style="min-height: 65vh;">
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <h1>Administradores de Unidades</h1>
            </div>
        </div>
    </div>
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

    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-unit-admin">Cadastrar administrador de unidade</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>nome</th>
                        <th>Unidade</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($unitAdmins as $unitAdmin)
                    <tr>
                        <td>{{$unitAdmin->user->name}}</td>
                        <td>{{$unitAdmin->InstitutionalUnit->name}}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#modal-confirm-delete"
                                onclick="onClickModalConfirmDelete('{{$unitAdmin->id}}','{{$unitAdmin->institutionalUnit->name}}')">
                                Apagar
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div id="modal-unit-admin" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Cadastro de administrador de unidade</h3>
                </div>
                <div class="modal-body">
                    <form class="form" method="post" action="{{ route('unit_admin.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nome</label>
                            <input id="name" name="name" class="form-control" type="text" placeholder="Nome completo" required>
                        </div>
                        <div class="form-group">
                            <label>E-mail para login</label>
                            <input id="email" name="email" class="form-control" type="email" required>
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input id="password" name="password" class="form-control" type="password" required>
                        </div>
                        <div class="form-group">
                            <label>Unidade da Instituição</label>
                            <select id="unit-id" name="unit-id" class="form-control">
                                @foreach($institutionalUnits as $unit)
                                <option value="{{ $unit->id }}">{{$unit->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input id="submit-unit-admin-form" type="submit" value="Cadastrar" hidden>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <label for="submit-unit-admin-form" class="btn btn-primary">Cadastrar</label>
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
                    <p id="confirmation-text">Tem certeza?</p>
                    <form method="post" action="">
                        @csrf
                        @method('delete')
                        <input id="submit-confirm-delete" type="submit" hidden>
                    </form>
                </div>
                <div class="modal-footer">
                    <label for="submit-confirm-delete" class="btn btn-danger">Remover</label>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="onClickCancelDelete()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts-bottom')
<script src="{{ asset('js/unitAdmin.js') }}"></script>

@endsection