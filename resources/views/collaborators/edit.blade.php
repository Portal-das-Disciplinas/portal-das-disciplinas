@extends('layouts.app')
@section('content')
@section('title')
Edição de Colaborador
@endsection
@section('content')
<div class="d-flex flex-column align-items-center w-100">
    <h1>Cadastro de Colaborador</h1>
    @if($errors->any())
    <h3 class="alert alert-danger w-100 text-center">
        {{$errors->first()}}

    </h3>
    @endif
    <div class="w-100">
        <div class=" d-flex flex-row flex-wrap justify-content-center align-items-start p-4 w-100">

            <div class="d-flex flex-column justify-content-center align-items-center mr-4">
                <img class="img-thumbnail" src="{{ $collaborator->urlPhoto ? '/storage/'.$collaborator->urlPhoto: asset('img/profiles_img/user2.png')}}" width="200px">
                <form id="form-collaborator-photo" class="mt-2" action="{{route('collaborators.update.photo',$collaborator->id)}}" method="post" enctype="multipart/form-data">
                    @method('PUT')
                    @csrf
                    <label class="btn btn-primary btn-sm" for="photo" name="photo">Alterar Foto</label>
                    <input class="d-none" id="photo" name="photo" type='file' onchange="submitImage()">
                </form>
                <form class="mt-2" action="{{route('collaborators.delete.photo', $collaborator->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">remover</button>
                </form>

            </div>



            <form id="collaborators-form" class="d-flex flex-column w-75" action="{{route('collaborators.update',$collaborator->id)}}" enctype="multipart/form-data" method='post'>
                @method('PUT')
                @csrf
                <label for="name">Nome</label>
                <input id="name" name="name" type=text class="form-control" value="{{$collaborator->name}}" placeholder="Nome e Sobrenome" required>
                <label for="email">e-mail</label>
                <input id="email" name="email" type="email" class="form-control" value="{{$collaborator->email}}" placeholder="E-mail" required>
                <label for="bond">Vínculo</label>
                <input id="bond" name="bond" type="text" class="form-control" value="{{$collaborator->bond}}" placeholder="bolsista, voluntário..." required>
                <label for="role">Função</label>
                <input id="role" name="role" type=text class="form-control" value="{{$collaborator->role}}" placeholder="Desenvolvedor, Designer, ..." required>
                <label for="lattes">Lattes</label>
                <input id="lattes" name="lattes" type="text" class="form-control" value="{{$collaborator->lattes}}" placeholder="Endereço do currículo latttes">
                <label for="github">Github</label>
                <input id="github" name="github" type="text" class="form-control" value="{{$collaborator->github}}" placeholder="Github">
                <div>
                    <label for="active">Ativo</label>
                    <input id="active" name="active" type="checkbox" @if($collaborator->active) checked @endif>
                </div>
                <div>
                    <label for="isManager">Coordenador</label>
                    <input id="isManager" name="isManager" type="checkbox" @if($collaborator->isManager) checked @endif>
                </div>
                <div class="d-flex justify-content-end">
                    <a id="btn-cancel" href="{{route('information')}}" class="mr-4">Cancelar</a>
                    <button class="btn btn-success" type="submit">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function submitImage() {
        document.querySelector('#form-collaborator-photo').submit();
    }
</script>


@endsection