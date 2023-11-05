@extends('discipline_performance_data.layout')

@section('title')
Semestre com dados de índices de rendimento
@endsection

@section('content2')
<div class="row mt-5">
    <div class="col-md-12 d-flex justify-content-center">
        <h2 style="text-align:center">Semestres com dados de índices de desempenho</h2>
    </div>
</div>
@if(session('status'))
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h3>{{session('status')}}</h3>
        </div>
    </div>
</div>
@endif

@if($errors->has('delete'))
<div class="row">
    <div class="col-md-6 offset-md-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h3>{{$errors->all()[0]}}</h3>
        </div>
    </div>
</div>
@endif

@if(count($semesterPerformanceData) > 0)
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover table-bordered table-small">
            <thead>
                <tr>
                    <th scope="col" style="text-align:center">Semestre</th>
                    <th scope="col" style="text-align:center">Quantidade de turmas</th>
                    <th scope="col" style="text-align:center">Contem erros</th>
                    <th scope="col" style="text-align:center">Último dado obtido em:</th>
                    <th scope="col" style="text-align:center">Pesquisa realizada em:</th>
                </tr>
            </thead>
            @foreach($semesterPerformanceData as $data)
            <tr @if($data->{'data_search_complete'} && $data->{'has_errors'})
                class='table-danger'
                @elseif($data->{'data_search_complete'})
                class=""
                @else
                class='table-info' 
                @endif
                >
                <td scope="row" style="text-align:center"><b>{{$data->year}}</b>.<b class="text-secondary">{{$data->period}}</b></td>
                <td scope="row" style="text-align:center">{{$data->{'data_amount'} }} {{$data->{'data_amount'}==1? 'turma':'turmas'}}</td>
                @if(!$data->{'data_search_complete'})
                <td scope="row" style="text-align:center"> - </td>
                @elseif($data->{'has_errors'})
                <td scope="row" style="text-align:center">SIM</td>
                @else
                <td scope="row" style="text-align:center">NAO</td>
                @endif
                <td style="text-align:center">
                    @if(isset($data->{'last_data_created_at'}))
                    <small>
                        {{(sprintf('%02d',date_parse($data->{'last_data_created_at'})['day'])) 
                        .'/'.(sprintf('%02d',date_parse($data->{'last_data_created_at'})['month']))
                        .'/'.(date_parse($data->{'last_data_created_at'})['year'])
                        .' '.(sprintf('%02d',date_parse($data->{'last_data_created_at'})['hour']))
                        .':'.(sprintf('%02d',date_parse($data->{'last_data_created_at'})['minute'])) }}
                    </small>
                    @else
                    <small> - </small>
                    @endif

                </td>
                <td style="text-align:center">
                    @if($data->{'data_search_complete'})
                    <small>
                        {{(sprintf('%02d',date_parse($data->{'data_researched_at'})['day'])) 
                        .'/'.(sprintf('%02d',date_parse($data->{'data_researched_at'})['month']))
                        .'/'.(date_parse($data->{'data_researched_at'})['year'])
                        .' '.(sprintf('%02d',date_parse($data->{'data_researched_at'})['hour']))
                        .':'.(sprintf('%02d',date_parse($data->{'data_researched_at'})['minute'])) }}
                    </small>
                    @else
                    <small class="text-success"> executando ainda </small>
                    @endif

                </td>
                <td scope="row" style="text-align:center">
                    <form id="{{'form-delete-'. $data->id}}" method="POST" action="{{route('semester_performance_data.destroy',$data->id)}}">
                        @csrf
                        @method('DELETE')
                    </form>
                    <span onclick="openModalConfirm('{{$data->id}}')" class="text-danger" style="cursor:pointer;font-weight:700">apagar</span>
                </td>
            </tr>
            @endforeach
            <tbody>

            </tbody>
        </table>
    </div>

</div>
@else
<div class="row">
    <div class="col-md-12 d-flex justify-content-center">
        <h3 class="text-secondary">Sem dados</h3>
    </div>
</div>
@endif
<div id="modal-confirm" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title text-danger">ALERTA</h3>
            </div>
            <div class="modal-body">
                <p>Deseja realmente <b class="text-danger">apagar</b> os dados de todas as disciplinas referente à esse semestre?</p>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-secondary" data-dismiss="modal" onclick="onClickCancelDelete()">Cancelar</button>
                    <button class="btn btn-danger ml-2" onclick="deleteData(idDataToDelete)">Apagar</button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts-bottom')
<script>
    let idDataToDelete = null;

    function openModalConfirm(idData) {
        $('#modal-confirm').modal('show');
        idDataToDelete = idData;
    }

    function deleteData(idData) {
        document.querySelector('#form-delete-' + idData).submit();
    }

    function onClickCancelDelete() {
        idDataToDelete = null;
    }
</script>
@endsection