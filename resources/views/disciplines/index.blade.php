@extends('layouts.app')
@section('styles-head')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection
@section('content')
<section class='hero-section mb-4'>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center my-4 title-subject-container">
                <h1 class="title-subject display-title ">Portal das Disciplinas - {{$theme['PROJETO_SIGLA_SETOR_INSTITUICAO']}}</h1>
                <div class="row justify-content-center">
                    <p class='p-text mt-3  text-center col-md-10  larger-p'>{{$theme['PROJETO_DISCIPLINAS_DESCRICAO']}}
                    <p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class='disciplines remove-margin-bottom'>
    <div class="disciplines-container container">
        <h1 class='text-center text-white'>Disciplinas Cadastradas</h1>
        <p class='text-center p-text' style='color: #80A6C6'>Lista de disciplinas disponíveis no portal... sempre
            crescendo!
        </p>
        @auth
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-3 mt-5">
                <a name="createDisciplina" class="create-discipline btn btn-outline-light btn-block" href="{{ route('disciplinas.create') }}" role="button">Cadastrar
                    disciplina</a>
            </div>
        </div>
        @endauth

        <div class="row justify-content-md-center mt-5" style="margin-bottom:25px;">
            <div class="col-md-12">
                <form id="filter" class="row" action="{{ route('index') }}" method="GET">
                    @csrf
                    <div class="col-md-5 mb-3">
                        <select name="institutional-unit-id" class="form-control ">
                            <option value="">Todas as unidades</option>
                            @foreach($institutionalUnits as $unit)
                            <option value=" {{$unit->id}} "  {{$unitId == $unit->id ? 'selected' : ''}}>{{$unit->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <select id="course-id" name="course-id" class="form-control">
                            <option value="">Todos os cursos</option>
                            @foreach($courses as $course)
                            <option value=" {{$course->id}}" {{$courseId == $course->id ? 'selected' : ''}}>{{$course->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <select name="education-level-id" class="form-control">
                            <option value="">Todos os níveis</option>
                            @foreach($educationLevels as $educationLevel)
                            <option value=" {{$educationLevel->id}}" {{$educationLevelId == $educationLevel->id ? 'selected' : ''}}>{{$educationLevel->value}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <input id="name_discipline" type="text" class="form-control" placeholder="Nome da disciplina, temas, conceitos metodologias ou referências" aria-label="Caixa de pesquisa" aria-describedby="button-addon2" name='name_discipline' value="{{$disciplineName}}" />
                                <label for="name_discipline" class="text-white"><small>Digite, o nome da disciplina, temas, conceitos, metodologias ou referências <span class="text-warning">separados por vírgula</span></small></label>
                                {{--<div id="autocomplete-results" class="autocomplete-results mt-1 "></div> --}}
                            </div>
                            <div class="col-md-4">
                                <select name="emphasis" id="emphasis" class='form-control'>
                                    <option selected value=""> Todas as ênfases </option>
                                    @foreach($emphasis ?? '' as $emphase)
                                    <option value="{{ $emphase->id }}" {{$emphaseId == $emphase->id ? 'selected' : ''}}>{{ $emphase->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <div class="d-flex justify-content-end">
                                    <button id="pesquisar" class="btn btn-primary search-button" type="submit"><i class='fas fa-search search-icon'></i>Pesquisar</button>
                                </div>
                            </div>
                        </div>

                        <span id="texto-mostrar-filtros" class=" btn btn-outline-info text-warning mt-2 mb-2" data-toggle="collapse" data-target="#collapse-filters" role="button" aria-controls="#collapse-filters">Busca Avançada  <li class='fa fa-caret-down'></li></span>
                        <div id="collapse-filters" name="collapse-advanced-filters" class="collapse px-1 pb-2" style="border: solid 1px rgba(255,255,255,0.5);border-radius:5px">
                            @if(count($methodologies) > 0) 
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <span class="text-white">Clique nas metodologias abaixo que você deseja incluir no filtro</span>
                                    <div class="bg-white d-flex px-0 py-3 flex-wrap justify-content-start" style="border-radius:5px; max-height: 200px; overflow:auto">
                                        @foreach($methodologies as $methodology)
                                        <small id="{{'methodology-' . $loop->index }}" class="badge badge-secondary mx-2 my-2" style="cursor:pointer;" onclick="onClickMethodology('{{$loop->index}}')">{{$methodology->name}}</small>
                                        @endforeach
                                    </div>
                                </div>
                                <input id="filteredMethodologies" name='filtered-methodologies' type='hidden'>
                            </div>
                            @endif
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label class="text-white" for="select-professors">Professor</label>
                                    <select class="form-control" id="select-professors" name="professors">
                                        <option value="null">Todos os professores</option>
                                        @if(isset($professors))
                                        @foreach($professors as $professor)
                                        <option value="{{$professor->id}}" {{$professorId == $professor->id ? 'selected' : ''}}>{{$professor->name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div class="row mt-1 ">
                                <div class="col-md-12">
                                    <input id="check-filtro-classificacoes" name="check-filtro-classificacoes" type="checkbox" onclick="onCheckClassificationFilter(event)" {{isset($checkClassificationsFilter) ? 'checked' : ''}}>
                                    <label for="check-filtro-classificacoes" class="text-white" style="cursor:pointer;">Filtro por classificações</label>
                                </div>
                                <div class="col-md-12">
                                    <div id="collapse-classificacoes" class="row collapse">
                                        <div class="col-md-12">
                                            <div class="d-flex">
                                                <div class="mr-5">
                                                    <input id="filtro-classificacoes-caracteristica" name="filtro-classificacoes-caracteristica" type="checkbox" checked onchange="onClickClassificationFilterType(event)">
                                                    <label for="filtro-classificacoes-caracteristica" class="text-white" style="cursor:pointer"><small>Filtro por Característa</small></label>
                                                </div>
                                                <div>
                                                    <input id="filtro-classificacoes-detalhado" name="filtro-classificacoes-detalhado" type="checkbox" onchange="onClickClassificationFilterType(event)">
                                                    <label for="filtro-classificacoes-detalhado" class="text-white" style="cursor:pointer"><small>Filtro detalhado</small></label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="d-flex flex-column">
                                                <div id="area-caracteristica-predominante" class="bg-white" style="border-radius: 10px; border:solid 1px lightgray;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-center">
                                                                <h3>Característica predominante</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table">
                                                                <tbody>
                                                                    @foreach($classifications as $classification)
                                                                    <tr>
                                                                        <td><small class="font-weight-bold">{{$classification->name}}</small></td>
                                                                        <td>
                                                                            <div class="d-flex flex-column align-items-center">                                                   
                                                                                <input id="{{'predominant_classification_type_a' . $classification->id}}" type="radio" name="{{'classification' . $classification->id}}" value="type_a" 
                                                                                {{isset($selectedPredominantClassifications['classification' . $classification->id]) && ($selectedPredominantClassifications['classification' . $classification->id]) == 'type_a'? 'checked' : ''}} >
                                                                                <label for="{{'predominant_classification_type_a' . $classification->id}}" style="cursor:pointer;"><small class="font-weight-bold">{{$classification->{'type_a'} }}</small></label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column align-items-center">
                                                                                <input id="{{'predominant_classification_neutral' . $classification->id}}" type="radio" name="{{'classification' . $classification->id}}" value="neutra" 
                                                                                {{!isset($selectedPredominantClassifications['classification' . $classification->id]) 
                                                                                    || ((($selectedPredominantClassifications['classification' . $classification->id]) != 'type_a') && ($selectedPredominantClassifications['classification' . $classification->id]) != 'type_b') ? 'checked' : ''}}>
                                                                                <label for="{{'predominant_classification_neutral' . $classification->id}}" style="cursor:pointer;"> <small class="text-secondary font-weight-bold">Neutra</small></label>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column align-items-center">
                                                                                <input id="{{'predominant_classification_type_b' . $classification->id}}" type="radio" name="{{'classification' . $classification->id}}" value="type_b"
                                                                                {{isset($selectedPredominantClassifications['classification' . $classification->id]) && ($selectedPredominantClassifications['classification' . $classification->id]) == 'type_b'? 'checked' : ''}}>
                                                                                <label for="{{'predominant_classification_type_b' . $classification->id}}" style="cursor:pointer;"><small class="font-weight-bold">{{$classification->{'type_b'} }}</small></label>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div id="area-filtro-detalhado" class="bg-white d-none" style="border-radius: 10px; border:solid 1px lightgray;">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="d-flex justify-content-center">
                                                                <h3>Filtro detalhado</h3>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table id="table-classifications-detail" class="table">
                                                                <tbody>
                                                                    @foreach($classifications as $classification)
                                                                    <tr>
                                                                        <td>
                                                                            <input id="{{'classification_detail_active' . $classification->id}}" name="{{'classification_detail_active' . $classification->id}}" type="checkbox"
                                                                            {{isset($selectedActiveClassications['classification_detail_active' . $classification->id]) ? 'checked' : ''}}>
                                                                            <label for="{{'classification_detail_active' . $classification->id}}" style="cursor:pointer;"><small class="font-weight-bold">{{$classification->name}}</small></label>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex justify-content-center align-items-center flex-column">
                                                                                <label for="{{'classification_detail_type_a_value' . $classification->id}}"><small id="{{'classification_detail_type_a_name' . $classification->id }}" class="text-primary font-weight-bold" style="cursor:pointer;">{{$classification->{'type_a'} }}</small></label>
                                                                                <input type="radio" id="{{'classification_detail_type_a_value' . $classification->id}}" name="{{'classification_detail' . $classification->id .'radio'}}" value="type_a" onchange="onSelectClassificationTypeA(event, '{{$classification->id}}' )"
                                                                                {{(!isset($selectedDetailedClassificationTypes['classification_detail' . $classification->id .'radio'])) || (isset($selectedDetailedClassificationTypes['classification_detail' . $classification->id .'radio']) && ($selectedDetailedClassificationTypes['classification_detail' . $classification->id .'radio'] == 'type_a')) ? 'checked' : ''}}>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column align-items-center">
                                                                                <input id="{{'classification_detail' . $classification->id}}" type="range" min="0" max="100"name="{{'classification_detail' . $classification->id}}" style="appearance:none; background:lightgray;height:8px;width:75px;" oninput="onChangeClassificationSlider(event)" step="5"
                                                                                    value={{ isset($classificationValues['classification_detail' . $classification->id]) ?  $classificationValues['classification_detail' . $classification->id] : '0'}}>
                                                                                <span id="{{'classification_detail' . $classification->id . 'info_value'}}" class="text-primary mt-3 font-weight-bold">
                                                                                     >={{ isset($classificationValues['classification_detail' . $classification->id]) ?  $classificationValues['classification_detail' . $classification->id] : '0'}}
                                                                                </span>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div class="d-flex flex-column justify-content-center align-items-center">
                                                                                <label for="{{'classification_detail_type_b_value' . $classification->id}}"><small id="{{'classification_detail_type_b_name' . $classification->id }}" class="text-secondary font-weight-bold" style="cursor:pointer;">{{$classification->{'type_b'} }}</small></label>
                                                                                <input type="radio" id="{{'classification_detail_type_b_value' . $classification->id}}" name="{{'classification_detail' . $classification->id .'radio'}}" value="type_b" onchange="onSelectClassificationTypeB(event, '{{$classification->id}}' )"
                                                                                {{isset($selectedDetailedClassificationTypes['classification_detail' . $classification->id .'radio']) && ($selectedDetailedClassificationTypes['classification_detail' . $classification->id .'radio'] == 'type_b') ? 'checked' : ''}}>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <input id="check-filtro-aprovacao" name="check-filtro-aprovacao" type="checkbox" onchange="onChangeCheckFilterApproval(event)" {{$checkApprovalFilters =='on' ? 'checked' : ''}}>
                                    <label class="text-white" for="check-filtro-aprovacao" style="cursor:pointer">Filtro por dados de aprovação</label>
                                </div>
                                <div class="col-md-5 ">
                                    <div class="form-row">
                                        <div class="col-md-4 mb-1">
                                            <select id="select-tipo-aprov" class="form-control mr-1" name="tipo-aprov">
                                                <option value="aprov" {{$selectTipoAprov == 'aprov' ? 'selected' : ''}}>aprovação</option>
                                                <option value="reprov" {{$selectTipoAprov == 'reprov' ? 'selected' : ''}}>reprovação</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-1">
                                            <select id="select-comparacao" class="form-control mr-1" name="comparacao">
                                                <option value="maior" {{$comparacao == 'maior' ? 'selected' : ''}}>maior que</option>
                                                <option value="menor" {{$comparacao == 'menor' ? 'selected' : ''}}>menor que</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="d-flex align-items-center">
                                                <input id="input-valor-comparacao" type="number" min="0" max="100" 
                                                    class="form-control" name="valor-comparacao" value="{{$valorComparacao}}">
                                                <span class="text-white ml-2">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <label class="text-white">Ano</label>
                                                </div>
                                                <div class="col-md-9">
                                                    <select id="select-ano-aprov" name="ano-aprov" class="form-control">
                                                        <option value="null">Todos</option>
                                                        @for($i=2014;$i < date('Y');$i++) 
                                                        <option value="{{$i}}" {{$anoAprov == $i ? 'selected' : ''}}>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-row">
                                                <div class="col-md-5">
                                                    <label class="text-white">Período</label>
                                                </div>
                                                <div class="col-md-7">
                                                    <select id="select-periodo-aprov" name="periodo-aprov" class="form-control" style="background-color: lightgray;" disabled>
                                                        <option value="null">Todos</option>
                                                        @for($i=1; $i <= 6; $i++)
                                                        <option value="{{$i}}" {{$periodoAprov == $i ? 'selected' : ''}}>{{$i}}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
                                    <div class="d-flex justify-content-start">
                                        <button id="pesquisar-bottom" class="btn btn-primary search-button" type="submit"><i class='fas fa-search search-icon'></i>Pesquisar</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </div>
        </div>

        @isset($disciplines)
        @if(count($disciplines) == 0)
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    <h2 class="text-white"> Nenhuma disciplina encontrada </h2>
                </div>
            </div>

        </div>
        @else
        <div class="row pb-5">
            @foreach($disciplines as $discipline)
            <div class="col-12 col-sm-6 col-lg-3 mb-5 ">
                <div class="discipline-card shadow light-border-radius" style="background-color: white;">
                    <div class=" d-flex justify-content-end bg-primary">
                        <small>
                            <strong class="text-white mr-1">
                                @if(isset($discipline->institutionalUnit))
                                {{isset($discipline->InstitutionalUnit->acronym) ? $discipline->institutionalUnit->acronym : Str::limit($discipline->institutionalUnit->name,32,'...')}}
                                @else
                                Portal das Disciplinas
                                @endif
                            </strong>
                        </small>
                    </div>
                    <div style="height: 245px;">
                        @if(!is_null($discipline['trailer']) && ($discipline->trailer->view_url != ""))
                        <div class="teacher-video-container">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item light-border-radius" src="{{$discipline->trailer->view_url}}" allowfullscreen></iframe>
                            </div>
                        </div>
                        @else
                        <img src="{{ asset('img/IMD_logo.svg') }}" class="card-img-top light-border-radius" alt="..">
                        @endif
                        <div class="card-top-contsainer px-3 pt-3">
                            <div>
                                @if(strlen($discipline->name) >= 56)
                                <strong class="card-title">{{ $discipline->name}}</strong>
                                @else
                                <h3 class="card-title">{{$discipline->name}}</h3>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between flex-column px-3" style="height:135px;" >
                        <div>
                                <div class="d-flex justify-content-center w-100">
                                    <button type="button" class="btn btn-outline-primary  w-100" data-toggle="modal" data-target="{{'#modal-description-'. $discipline->id}}">
                                        Ver Sinopse
                                    </button>
                                    <div class="modal fade" id="{{'modal-description-'. $discipline->id}}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="{{'#modal-description-'. $discipline->id}}">{{$discipline->name}}</h4>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(isset($discipline['description']) && strlen($discipline['description']) > 0)
                                                    {{$discipline['description']}}
                                                    @else
                                                    <p class="text-secondary">Sinopse não disponível.</p>
                                                    <p class="text-secondary">Clique em no botão "Ver disciplina" para saber mais sobre a disciplina!</p>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                                                    <a href="{{ route('disciplinas.show', $discipline['id']) }}" class="btn btn-primary">
                                                        Ver disciplina
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="card-bottom-container">
                            <a href="{{ route('disciplinas.show', $discipline['id']) }}" class="view-more-btn btn w-100 p-text">
                                Ver disciplina
                            </a>
                        </div>
                        
                        <div>
                            @auth
                            <div class='d-flex justify-content-end'>
                                @if(Auth::user()->canDiscipline($discipline['id']))
                                <div class="dropdown show">
                                    <div class="advanced-options d-flex align-items-center mt-2 p-text" data-toggle="dropdown">
                                        <a class='mr-2' style="cursor:pointer">Opções avançadas</a>
                                        <i class="fas fa-caret-down"></i>
                                    </div>
                                    <div class="user-dropdown dropdown-menu">

                                        <form action=" {{ route('disciplinas.destroy', $discipline['id']) }}" class="dropdown-item" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100 p-text " value="Apagar">Apagar</button>
                                        </form>
                                        <form action=" {{ route('disciplinas.edit', $discipline['id']) }}" class="dropdown-item" method="get" class='dropdown-item'>
                                            @csrf
                                            @method('UPDATE')
                                            <button type="submit" class="btn btn-warning w-100 p-text m-0" value="Editar">Editar</button>
                                        </form>

                                    </div>
                                </div>
                                @endif
                            </div>

                            @endauth
                        </div>

                    </div>
                    <div  class="d-flex flex-column justify-content-end" style="height:60px;">
                       
                        <div class="card-footer smaller-p py-0 m-0 w-100">
                        @if (isset($discipline->professor->name))
                            {{$discipline->professor->name}}
                        @else
                            Indefinido
                        @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        @endisset
        <div class="row mt-5">
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    {{$disciplines->links()}}
                </div>
            </div>
        </div>
    </div>

</section>

<div id="modal-methodology-info" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 id="methodology-name" class="modal-title text-primary">Nome da metodologia</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p id="methodology-description">Descrição da metodologia</p>
      </div>
      <div class="modal-footer">
        <button id="btn-remove-or-select" class="btn btn-primary" onclick="addMethodologyToSelected()">Selecionar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>



<script src="{{ asset('js/indexClassificationForm.js') }}"></script>
<script src="{{ asset('js/indexSlider.js') }}"></script>
{{--<script src="{{ asset('js/disciplineAutoComplete.js') }}"></script>--}}
@endsection
@section('scripts-bottom')
<script src="{{ asset('js/disciplines/disciplineFilters.js')}}"></script>
<script>
    let methodologies = @json($methodologies);
</script>
<script src="{{asset('js/disciplines/methodologySelect.js')}}"></script>
<script>
    let oldFilteredMethodologies = @json($filteredMethodologies);
    setFilteredMethodologies(oldFilteredMethodologies);
    @if(isset($checkApprovalFilters))
    enableApprovalFilters(true);
    @else
    enableApprovalFilters(false);
    @endif
    updateDetailedClassificationsStyles(@json($classifications));

</script>

@endsection