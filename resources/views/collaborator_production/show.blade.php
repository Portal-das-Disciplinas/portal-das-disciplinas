@extends('layouts.app')

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/about.css')}}">
@endsection

@section('content')
<div class="container mt-5" style="min-height:100vh">
    @if($errors->any())
    <div class="row">
        <div class="col-md-12 alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>

        </div>
    </div>
    @endif
    @if(session('feedback_ok'))
    <div class="row">
        <div class="col-md-12 alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p style="text-align:center">{{session('feedback_ok')}}</p>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-3">
            <div class="d-flex flex-column align-items-center mb-3">
                @if(isset($collaborator->urlPhoto))
                <img class="clip-path regular-image" src="{{'/storage/' . $collaborator->urlPhoto}}">
                @else
                <img class="clip-path regular-image" src="{{asset('img/profiles_img/user2.png')}}">
                @endif
                <strong> {{$collaboratorName}}</strong>
                <h4 class="text-secondary py-2" style="text-align:center; line-height:0.9">{{$collaborator->role}}</h4>
                @if(isset($collaborator->github) && $collaborator->github != '')
                <a href="{{$collaborator->github}}" target="_blank" class="d-flex align-items-center"><img src="/img/github-mark.svg" width="20px">Github</a>
                @endif
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 mb-3">

                    <h3 class="text-secondary" style="text-align:center">Produções do Colaborador no Portal das Disciplinas</h3>
                </div>
            </div>
            @if(Auth::user() && Auth::user()->isAdmin)
            <div class="row">
                <div class="col-md-12 d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm mb-3" onclick="showModalCreateCollaboratorProductions('{{$collaborator->id}}')">Cadastrar produções</button>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12 d-flex justify-content-center">
                    {{$collaboratorProductions->links()}}
                </div>
            </div>
            @foreach($collaboratorProductions as $production)
            <div class="row">
                <div class="col-md-12 card p-2 mb-3" style="box-shadow: 2px 2px 5px rgba(0,0,0,0.1)">

                    <p class="smaller-p"><b>{{$production->brief}}</b></p>
                    <div class="d-flex justify-content-between">
                        @if($production->details)
                        <a class="smaller-p" data-toggle="collapse" href="{{'#collapseDetails' . $production->id}}" role="button" aria-expanded="false" aria-controls="{{'collapseDetails' . $production->id}}">
                            Detalhes
                        </a>
                        @endif
                        @if(Auth::user() && Auth::user()->isAdmin)
                        @if(!$production->details)
                        <small class="text-secondary">sem detalhes</small>
                        @endif
                        <div class="d-flex justify-content-between mb-1">
                            <button class="btn btn-outline-primary btn-sm " data-toggle="modal" data-target="#modal-update" onclick="onClickOpenModal('{{$production->id}}','{{$production->brief}}','{{$production->details}}')">Editar</button>
                            <button class="btn btn-outline-danger btn-sm ml-3" onclick="openConfirmDeleteModal('{{$production->id}}')">Deletar</button>
                        </div>
                        @endif
                    </div>

                    <div class="collapse" id="{{'collapseDetails' . $production->id}}">
                        <div class="card card-body">
                            <p class="smaller-p text-secondary">{{$production->details}}</p>
                        </div>
                    </div>

                </div>
            </div>
            @endforeach
            <div class="row mt-5">
                <div class="col-md-12 d-flex justify-content-center">
                    {{$collaboratorProductions->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
<div id="modal-update" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Atualização</h3>
            </div>
            <div class="modal-body">
                <form class="form" method="post" action="{{route('collaborator_production.update')}}">
                    @csrf
                    @method('PUT')
                    <input id="productionId" name="productionId" type="hidden">
                    <div class="form-group">
                        <label for="productionBrief">Breve descrição</label>
                        <input id="productionBrief" name="productionBrief" type="text" class="form-control" minlength="5" maxlength="84">
                    </div>
                    <div class="form-group">
                        <label for="productionDetails">Detalhes</label>
                        <textarea id="productionDetails" name="productionDetails" maxlength="256" class="form-control" placeholder="Opcional"></textarea>
                    </div>
                    <div>
                        <button class="btn btn-primary btn-sm" type="submit">Atualizar</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div id="modal-confirm-delete" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Confirmação</h3>
            </div>
            <div class="modal-body">
                <h3>Tem certeza que deseja apagar esse dado?</h3>
                <form class="form" method="post" action="{{route('collaborator_production.delete')}}">
                    @csrf
                    @method('DELETE')
                    <input id="productionId" name="productionId" hidden>
                    <input id="submitDelete" type="submit" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <label for="submitDelete" class="btn btn-danger btn-sm">Confirmar</label>
                    <label class="btn btn-secondary btn-sm ml-4" data-dismiss="modal">Cancelar</label>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalCreateCollaboratorProductions" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Produção do desenvolvedor no portal</h3>
            </div>
            <div class="modal-body">
                <form id="formCollaboratorProductionsCreate" action="{{route('colalborators_productions.store_list_json')}}" method="POST">
                    @csrf
                    <div id="fields"></div>
                    <input id="productionCollaboratorId" name="productionCollaboratorId" hidden>
                    <input id="collaboratorProductionsJSON" name="collaboratorProductionsJSON" hidden>
                    <div class="d-flex justify-content-end">
                        <button id="btnSubmitProductions" type="submit" class="btn btn-success" onclick="btnSaveProductions()">Salvar</button>
                    </div>
                </form>
                <button class="btn btn-outline-primary btn-sm" onclick="addField('formCollaboratorProductionsCreate')">Adicionar Campo</button>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts-bottom')
<script src="{{asset('js/collaboratorProductions.js')}}"></script>
<script>
    inputProductionId = document.querySelector('#productionId');
    inputProductionBrief = document.querySelector('#productionBrief');
    inputProductionDetails = document.querySelector('#productionDetails');

    function onClickOpenModal(productionId, productionBrief, productionDetails) {
        inputProductionId.value = productionId;
        inputProductionBrief.value = productionBrief;
        inputProductionDetails.innerHTML = productionDetails;
    }

    function openConfirmDeleteModal(productionId) {
        document.querySelector('#modal-confirm-delete #productionId').value = productionId;
        $('#modal-confirm-delete').modal('show');
    }
</script>

@endsection