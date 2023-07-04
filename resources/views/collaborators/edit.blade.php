@extends('layouts.app')
@section('title')
Edição de Colaborador 
@endsection
@section('robots')
noindex, follow
@endsection
@section('styles-head')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" 
                       integrity="sha512-cyzxRvewl+FOKTtpBzYjW6x6IAYUCZy3sGP40hn+DQkqeluGRCax7qztK2ImL64SA+C7kVWdLI6wvdlStawhyw==" 
                       crossorigin="anonymous" 
                       referrerpolicy="no-referrer" />
<style>
    .cropper-view-box,
    .cropper-face {
      border-radius: 50%;
    }
</style>

@endsection
@section('content')
<div class="d-flex flex-column align-items-center w-100 mb-5 mt-5">
    <h1>Edição de Colaborador</h1>
    @if($errors->any())
    <h3 class="alert alert-danger w-100 text-center">
        {{$errors->first()}}
    </h3>
    @endif
</div>

<div class="w-100">
    <form id="collaborators-form" class="container" action="{{route('collaborators.update',$collaborator->id)}}" enctype="multipart/form-data" method='post'>
        @method('PUT')
        @csrf
        <div class="row">
            <div class="d-flex flex-column justify-content-start align-items-center col-md-4">
                <div class="d-flex flex-column justify-content-center">
                    <div class="mb-3" style="max-width:200px">
                        <img id="photoImg" class="d-block w-100" src="{{ $collaborator->urlPhoto ? '/storage/'.$collaborator->urlPhoto : asset('img/profiles_img/user2.png')}}">
                    </div>
                    <label class="btn btn-outline-primary btn-sm mb-3" for="photo" name="labelPhoto">Alterar Foto</label>
                    <input class="d-none" id="photo" name="photo" type='file' onchange="changePhoto(event)">
                    <input id="imageChanged" type="checkbox" name="imageChanged" hidden>
                    <button class="btn btn-outline-danger" onclick="deletePhoto(event)">remover</button>
                </div>
            </div>

            <div class="col-md-6">
                <label for="name">Nome</label>
                <input id="name" name="name" type=text class="form-control" value="{{$collaborator->name}}" placeholder="Nome e Sobrenome" required>
                <label for="email">e-mail</label>
                <input id="email" name="email" type="email" class="form-control" value="{{$collaborator->email}}" placeholder="E-mail">
                <label for="bond">Vínculo</label>
                <input id="bond" name="bond" type="text" class="form-control" value="{{$collaborator->bond}}" placeholder="bolsista, voluntário..." required>
                <label for="role">Função</label>
                <input id="role" name="role" type=text class="form-control" value="{{$collaborator->role}}" placeholder="Desenvolvedor, Designer, ..." required>
                <label for="lattes">Lattes</label>
                <input id="lattes" name="lattes" type="url" class="form-control" value="{{$collaborator->lattes}}" placeholder="https://" >
                <label for="github">Github</label>
                <input id="github" name="github" type="url" class="form-control" value="{{$collaborator->github}}" placeholder="https://">
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

                <p class="mt-4"> Links </p>
                <div id="links">
                    <!--render links -->
                </div>
                <div>
                    <label class=" btn btn-info d-inline-block text-white" onclick="addLinkField()">Adicionar Link</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-end col-md-12">
                    <a id="btn-cancel" href="{{route('information')}}" class='mr-4'>Cancelar</a>
                    <button class="btn btn-success" type="button" onclick="submitForm(event)">Atualizar</button>
                </div>
            </div>
        </div class="row">
    </form>

</div>

<script>
    let collaborator = @json($collaborator);
    let collabLinks = @json($collaborator->links);
    let links = [];

    if (collabLinks) {
        links = collabLinks;
        renderLinks();
    }
    /*Quando o usuário clica em cancelar o upload,  a lista files do input[type=file] 
      fica vazia, então a variável previous file guarda o último arquivo
      antes do usuário ter clicado em cancelar.*/
    let previousFile = null;

    function changePhoto(event) {
        event.preventDefault();

        /*Quando target.files.lenght == 0 significa que o usuário
          cancelou o upload ou deletou a imagem                 */
        if(event.target.files.length == 0){
            if(document.querySelector('#photoImg').src != '/img/profiles_img/user2.png'){
                if(previousFile != null){
                    let dt = new DataTransfer();
                    dt.items.add(previousFile);
                    document.querySelector("#photo").files = dt.files;
                    previousFile = dt.files[0];
                    hasPhoto = false;
                }  
            }
            return false;
        }
        document.querySelector("#imageChanged").checked = true;
        cropper.replace(document.querySelector('#photoImg').src);
        
        let reader = new FileReader();
        reader.onload = (e) => {
            document.querySelector('#photoImg').src = e.target.result;
            cropper.replace(document.querySelector('#photoImg').src);
            previousFile = document.querySelector("#photo").files[0];
            hasPhoto = true; 
        }

        reader.onerror = (event) => {
            alert("erro ao carregar a imagem do seu dispositivo");
            document.querySelector('#photoImg').src = '/img/profiles_img/user2.png';
            hasPhoto = false;
        }
        let file = event.target.files[0];
        previousFile = event.target.files[0];
        reader.readAsDataURL(file);
    }
    let hasPhoto = false;
    window.onload = ()=>{
        if(document.querySelector('#photoImg').src != '/img/profiles_img/user2.png'){
            hasPhoto = true;
        }
        
        cropper.replace(document.querySelector('#photoImg').src);
    }

    function deletePhoto(event) {
        event.preventDefault();
        document.querySelector("#imageChanged").checked = true;
        document.querySelector('#photoImg').src = '/img/profiles_img/user2.png';
        document.querySelector('#photo').files = new DataTransfer().files;
        cropper.replace(document.querySelector('#photoImg').src);
        previousFile = null;
        hasPhoto = false;
    }

    let cropper = new Cropper(document.querySelector('#photoImg'),{
        aspectRatio:1,
        dragMode:'none',
        cropBoxMovable:true,
        cropBoxResizable:false,
        guides:false,
        viewMode:1,
        minCropBoxWidth:200,
        minCropBoxHeight:200,
 
    });
    
    function submitForm(event){
        event.preventDefault();
        
        let inputPhoto = document.querySelector('#photo');
        if(hasPhoto){
            let dt = new DataTransfer();
            let img = cropper.getCroppedCanvas().toBlob((blob)=>{
            croppedFile = new File([blob], "photo");
            dt = new DataTransfer();
            dt.items.add(croppedFile);
            let inputPhoto = document.querySelector('#photo');
            inputPhoto.files = dt.files;
            document.querySelector('#collaborators-form').submit();
            });
        }
        else{
            
            document.querySelector('#collaborators-form').submit();
        }
        
    }

    function renderLinks() {

        var html = "";
        links.forEach(function(link, i) {
            html += "<div class='mb-4'>" +
                "<input class='form-control' name='linkName[]' type='text' placeholder='Twitter, Instagram, Facebook, etc...' required value='" + link.name + "'>" +
                "<input class='form-control mt-1' name='linkUrl[]' type='url' placeholder='http://' required value='" + link.url + "'>" +
                "<input name='linkId[]' type='hidden' value='" + link.id + "'>" +
                "<label id = '" + link.id + "'class='btn btn-link text-danger' onclick='deleteLink(" + i + ")'>remover</label>" +
                "</div>"
        });
        document.querySelector('#links').innerHTML = html;

    }

    function addLinkField() {
        var linkNames = document.querySelectorAll("input[name='linkName[]']");
        var linkUrls = document.querySelectorAll("input[name='linkUrl[]']");
        for (var i = 0; i < linkNames.length; i++) {
            links[i].name = linkNames[i].value;
            links[i].url = linkUrls[i].value;
        }
        links.push({
            name: "",
            url: ""
        });
        renderLinks();

    }

    function deleteLink(index) {
        var linkNames = document.querySelectorAll("input[name='linkName[]']");
        var linkUrls = document.querySelectorAll("input[name='linkUrl[]']");
        for (var i = 0; i < linkNames.length; i++) {
            links[i].name = linkNames[i].value;
            links[i].url = linkUrls[i].value;
        }
        links = links.filter(function(link, i) {
            if (index != i) {
                return link;
            }
        });
        renderLinks();
    }
</script>

@endsection

@section('scripts-head')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js" 
    integrity="sha512-6lplKUSl86rUVprDIjiW8DuOniNX8UDoRATqZSds/7t6zCQZfaCe3e5zcGaQwxa8Kpn5RTM9Fvl3X2lLV4grPQ==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer">
</script>
@endsection