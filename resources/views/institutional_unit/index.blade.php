@extends('layouts.app')

@section('styles-head')

@endsection

@section('content')
<div class="container mt-3" style="min-height: 50vh;">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <h1>Unidades Institucionais</h1>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button class="btn btn-primary">Cadastrar Nova Unidade</button>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                @foreach($errors->all() as $error)
                    {{$error}}
                @endforeach
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            @if(session('success_message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">

                {{session('success_message')}}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12">
            <table class="table">
                <thead>

                </thead>
                <tbody>
                    @foreach($units as $unit)
                    <tr>
                        <td>
                            <p class="small-texts">
                                @if(isset($unit->acronym) && $unit->acronym != '')
                                {{$unit->acronym . ' - '}}
                                @endif
                                {{$unit->name}}
                            <p>
                        </td>
                        <td><button class="btn btn-warning btn-sm">Atualizar</button></td>
                        <td><button class="btn btn-danger btn-sm" data-toggle='modal' data-target="#modal-confirm-delete-{{$unit->id}}">Deletar</button></td>
                    </tr>
                    <div id="modal-confirm-delete-{{$unit->id}}" class="modal" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Tem certeza que deseja deletar a unidade</p>
                                    <p><strong>{{$unit->name}} ?</strong></p>
                                </div>
                                <div class="modal-footer">
                                    <form method="post" action="{{route('institutional_unit.destroy', $unit->id)}}">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>

                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach

                </tbody>
            </table>
        </div>

    </div>
</div>

@endsection