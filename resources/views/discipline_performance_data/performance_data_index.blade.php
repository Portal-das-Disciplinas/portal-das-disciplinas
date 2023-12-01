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
        <form action="{{route('performance.list')}}" method="GET">
            <div class="form-row">
                <div class="col-md-2">
                    <label>Código</label>
                    <input id="disciplineCode" name="disciplineCode" class="form-control" type="text" value="{{isset($disciplineCode) ? $disciplineCode : ''}}">
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
                        <option value="">TODOS</option>
                        @for($i = Date('Y'); $i > 2000; $i--)
                        <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <label>Período</label>
                    <select name="period" class="form-control">
                        <option value="">TODOS</option>
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
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
@if(isset($performanceData) && (count ($performanceData) == 0))
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-center alert-info p-3">
            <h3>SEM RESULTADOS @if(isset($disciplineCode)) para {{$disciplineCode}} @endif
                 @if(isset($year) && isset($period)) {{' - semestre ' . $year.'.'.$period}}
                 @elseif(isset($year) && !isset($perido)) {{' - ano: ' . $year}}
                 @elseif(!isset($year) && isset($period)) {{' - periodo: '. $period}}
                 @endif
            </h3>
        </div>
    </div>
</div>
@elseif(isset($performanceData))
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between">
        <div>
            @if(!isset($disciplineCode) && !isset($year) && !isset($period))
            <span>Exibindo todos os dados</span>
            @else
            <span>Resultados para @if(isset($disciplineCode)) <b>{{$disciplineCode}} </b>@endif
                       @if(isset($year) && !isset($period)) o ano: <b>{{$year}}</b>
                       @elseif(isset($year) && isset($period)) semestre: <b>{{$year . '.' . $period}}</b>
                       @elseif(isset($period)) periodo: <b>{{$period}}</b> @endif
            </span>
            @endif
        </div>
    </div>
</div>
@foreach($performanceData as $data)
<div class="row mb-3" style="box-shadow:2px 2px 5px rgba(0,0,0,0.2)">
    <div class="col-sm-3 py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <div class="d-flex flex-column mr-3">
            <h4 class="text-primary">{{$data->{'discipline_name'} }}</h4>
            <h4 class="text-secondary">{{$data['discipline_code']}}</h4>
            <h4>Turma: {{$data['class_code']}}</h4>
        </div>
    </div>
    <div class="col-md-3 py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <div class="d-flex flex-column mr-3">
            <h5>docente(s)</h5>
            @foreach(json_decode($data['professors']) as $professorName)
            <h5>{{$professorName}}</h5>
            @endforeach
        </div>
    </div>
    <div class="col-md-3 py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <h4 class="text-primary">{{$data['num_students']}} discentes</h4>
        <h4 class="text-success">{{$data['num_approved_students']}} discentes aprovados</h4>
        <h4 class="text-danger">{{$data['num_failed_students']}} discentes reprovados</h4>
    </div>
    <div class="col-md-2 d-flex flex-column justify-content-end align-items-center py-3" style="border-bottom-style:solid; border-width:1px; border-color:rgba(0,0,0,0.2)">
        <h4>Nota média</h4>
        <h3>{{$data['average_grade']}}</h3>
    </div>
</div>
@endforeach
<div class="row">
    <div class="col-md-12 d-flex justify-content-center" >
        {{$performanceData->links()}}
    </div>
</div>
@endif

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