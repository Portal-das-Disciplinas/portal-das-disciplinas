@extends('discipline_performance_data.layout')
@section('title')
Dados de desempenho das disciplinas
@endsection
@section('content2')
<div class="row">
    <div class="col-md-12 mt-5">
        <h2 style="text-align:center">Pesquisa de dados de desempenho de turmas</h2>
    </div>
</div>
<div class="row mt-5">
    <div class="col-md-12 mb-5">
        <form action="{{route('performance.index')}}" method="GET">
            <div class="form-row">
                <div class="col-md-2">
                    <label>Código</label>
                    <input id="disciplineCode" name="disciplineCode" class="form-control" type="text" value="{{$disciplineCode}}" required>
                </div>
                <div class="col-md-5" style="position:relative">
                    <label for="disciplineName">Pesquisar código pelo nome</label>
                    <input id="disciplineName" name="disciplineName" class="form-control" type="text" onkeyup="onKeyupLiveSearch()" placeholder="Encontrar código pelo nome da disciplina">
                    <div id="liveSearch" class="list-group fade-in" style="position:absolute; z-index:1000">
                        <!--Conteúdo gerado por javascript -->
                    </div>
                </div>
                <div class="col-md-2">
                    <label>Ano</label>
                    <select name="year" class="form-control">
                        @for($i = Date('Y'); $i > Date('Y')-20; $i--)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Período</label>
                    <select name="period" class="form-control">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                    </select>
                </div>
                <div class="col-md-1 py-3">
                    <div class="d-flex align-items-end justify-content-center w-100" style="height:60px">
                        <button id="searchDataButton" class="btn btn-primary btn-sm w-100" style="height:42px">Buscar</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@if($errors->any())
<div class="row">
    <div class="col-12">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <span>Ocorreram erros</span>
            @foreach($errors as $error)
            <ul>
                <li class="list-item">{{$error}}</li>
            </ul>
            @endforeach
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    </div>
</div>
@endif
@if(count ($performanceData) == 0)
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center alert-info p-3">
            <h3>Sem resultados de busca para o semestre {{$year}}.{{$period}}</h3>
        </div>
    </div>
</div>
@else
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between">
        <div>
            <span>Resultados para&nbsp;</span>
            <strong>{{$disciplineCode}}</strong>
            <strong class="text-secondary">&nbsp;semestre: {{$year . '.' . $period}}</strong>
        </div>
        <button class="btn btn-danger" data-toggle="modal" data-target="#modalConfirmAllData">Apagar dados da disciplina</button>
    </div>
</div>
@foreach($performanceData as $data)
<div class="row mb-3" style="box-shadow:2px 2px 5px rgba(0,0,0,0.2)">
    <div class="col-sm-2 py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <div class="d-flex flex-column mr-3">
            <h4 class="text-info">{{$data['discipline_code']}}</h4>
            <h4>Turma: {{$data['class_code']}}</h4>
        </div>
    </div>
    <div class="col-md-3 py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <div class="d-flex flex-column mr-3">
            <h5>docente(s)</h5>
            @foreach(json_decode($data['professors'])[0] as $professorNameJson)
            <h5>{{(json_decode($professorNameJson))->nome}}</h5>
            @endforeach
        </div>
    </div>
    <div class="col-md-3 py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <h4 class="text-primary">{{$data['num_students']}} discentes</h4>
        <h4 class="text-success">{{$data['num_approved_students']}} discentes aprovados</h4>
        <h4 class="text-danger">{{$data['num_failed_students']}} discentes reprovados</h4>
    </div>
    <div class="col-md-2 d-flex flex-column justify-content-center align-items-center py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <h4>Nota média</h4>
        <h3>{{$data['average_grade']}}</h3>
    </div>
    <div class="col-md-2 py-2 d-flex justify-content-center align-items-center">
        <button id="{{'idData-'.$data['id']}}" class="btn btn-sm btn-outline-danger" data-toggle="modal" data-target="#modalConfirmOneData" onclick="onClickDeleteData(event)">Excluir dado</button>
    </div>
</div>
@endforeach
@endif
<div id="modalConfirmOneData" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">CONFIRMAÇÃO</h3>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja apagar este dado?</p>
                <p class="text-secondary">A remoção deste dado pode resultar em alterações em pesquisas multi-turmas</p>
                <form class="form" id="formDeleteOneData" action="{{route('performance.delete')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input id="idData" name="idData" type="hidden">
                    <input id="inputSubmitDeleteOneData" type="submit" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end p-2">
                    <label for="inputSubmitDeleteOneData" class="btn btn-danger" type="submit">Confirmar</button>
                </div>
                <div class="d-flex justify-content-end p-2">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>

    </div>
</div>

<div id="modalConfirmAllData" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">CONFIRMAÇÃO</h3>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja apagar os <strong>TODOS</strong> dados de desempenho da disciplina {{$disciplineCode}} do semestre {{$year}}.{{$period}}?</p>
                <form class="form" id="formDeleteOneData" action="{{route('performance.delete_by_code_year_period')}}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input name="disciplineCode" type="hidden" value="{{$disciplineCode}}">
                    <input name="year" type="hidden" value="{{$year}}">
                    <input name="period" type="hidden" value="{{$period}}">
                    <input id="inputSubmitDeleteAllData" type="submit" hidden>
                </form>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end p-2">
                    <label for="inputSubmitDeleteAllData" class="btn btn-danger" type="submit">Confirmar</button>
                </div>
                <div class="d-flex justify-content-end p-2">
                    <button class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts-bottom')

<script>
    let disciplineNames = [];

    function onClickLi(event) {
        let element = event.target;
        let inputDisciplineName = document.querySelector("#disciplineName");
        inputDisciplineName.value = element.innerHTML.split(" - ")[1];
        document.querySelector("#disciplineCode").value = element.id.split(" ")[1];
        document.querySelector("#liveSearch").classList.add("d-none");
    }

    function onKeyupLiveSearch() {
        document.querySelector("#liveSearch").classList.add("d-none");
        $.ajax({
            url: '/discipline/code/name',
            method: 'GET',
            data: {
                'disciplineName': disciplineName.value
            },
            success: function(result) {
                disciplineNames = result;

                renderLI();
            },
            error: function(xhr, status, error) {
                let disciplineNames = [];
                renderLI();

            }
        });
    }

    function renderLI() {
        if (disciplineNames.length == 0) {
            document.querySelector("#liveSearch").classList.add("d-none");
            return;
        }
        let html = "";
        disciplineNames.forEach(function(discipline, index) {
            html += "<li class='list-group-item' id='code " + discipline.code + "' style='cursor:pointer' onclick='onClickLi(event)'>" + discipline.code + " - " + discipline.name + "</li>"
        });
        document.querySelector("#liveSearch").innerHTML = html;
        document.querySelector("#liveSearch").classList.remove("d-none");

    }
    let idToDeleteData = null;

    function onClickDeleteData(event) {
        idToDeleteData = event.target.id.split("-")[1];
        document.querySelector("#idData").value = idToDeleteData;
    }


    function onClickCancelDelete(event) {
        idToDeleteData = null;
        document.querySelector("#idData").value = null;
    }
</script>

@endsection