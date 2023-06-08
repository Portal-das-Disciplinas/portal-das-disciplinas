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
                <div class="container">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Data de entrada</label>
                            <input name="joinDate" type="date" class="form-control" value="{{$collaborator->joinDate}}">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label>Data de saída</label>
                            <input name="leaveDate" type="date" class="form-control" value="{{$collaborator->leaveDate}}">
                        </div>
                    </div>

                </div>
                <p class="mt-4">Links</p>
                <div id="links">
                    <!--render links -->
                </div>
                <div >
                    <label class=" btn btn-info d-inline-block text-white" onclick="addLinkField()">Adicionar Link</label>
                </div>
                
                <div class="d-flex justify-content-end">
                    <a id="btn-cancel" href="{{route('information')}}" class='mr-4'>Cancelar</a>
                    <button class="btn btn-success" type="submit">Atualizar</button>
                </div>
            </form>
            
        </div>
    </div>
</div>

<script>
    let collaborator = @json($collaborator);
    let collabLinks = @json($collaborator->links);

    
    let links = [];

    if(collabLinks){
        links = collabLinks;
        renderLinks();
    }
    function submitImage() {
        document.querySelector('#form-collaborator-photo').submit();
    }

    function renderLinks(){
        
        var html = "";
        links.forEach(function(link, i){
            html += "<div class='mb-4'>"+
                        "<input class='form-control' name='linkName[]' type='text' placeholder='Twitter, Instagram, Facebook, etc...' value='"+link.name+"'>"+
                        "<input class='form-control mt-1' name='linkUrl[]' type='text' placeholder='Url do link' value='"+link.url+"'>"+
                        "<input name='linkId[]' type='hidden' value='"+link.id +"'>"+
                        "<label id = '"+link.id+"'class='btn btn-link text-danger' onclick='deleteLink("+i+")'>remover</label>"+
                    "</div>"
        });
        document.querySelector('#links').innerHTML = html;
        
        

    }

    function addLinkField(){
        var linkNames = document.querySelectorAll("input[name='linkName[]']");
        var linkUrls = document.querySelectorAll("input[name='linkUrl[]']");
        for(var i=0;i< linkNames.length;i++){
            links[i].name = linkNames[i].value;
            links[i].url = linkUrls[i].value;
        }
        links.push({name:"", url:""});
        renderLinks();
        
    }

    function deleteLink(index){
        var linkNames = document.querySelectorAll("input[name='linkName[]']");
        var linkUrls = document.querySelectorAll("input[name='linkUrl[]']");
        for(var i=0;i< linkNames.length;i++){
            links[i].name = linkNames[i].value;
            links[i].url = linkUrls[i].value;
        }
        links = links.filter(function(link,i){
            if(index != i){
                return link;
            }
        }); 
        renderLinks();
    }
</script>


@endsection