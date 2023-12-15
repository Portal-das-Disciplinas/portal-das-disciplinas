@extends('discipline_performance_data.layout')

@section('title')
Agendamentos
@endsection
@section('content2')
<div class="row mt-5">
    <div class="col-md-12">
        <h2 style="text-align:center">Buscas agendadas de dados de desempenho na API</h2>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{'erro: '.$errors->first()}}
        </div>
        @endif

    </div>
</div>
<div class="row mt-5">
    <div class="col-sm-12">
        <div class="d-flex justify-content-start bg-primary">
            <form id="formSearchSchedules" class="w-100" method="GET" action="{{route('scheduling.index')}}">
                <div class="form-row">
                    <select id="selectSearchType" name="scheduleStatus" class="form-control" onchange=onSelectStatusSchedulesChange()>
                        <option value="PENDING" {{$searchType=='PENDENTES'? 'selected': ''}}> Agendamentos PENDENTES</option>
                        <option value="COMPLETE" {{$searchType=='COMPLETOS'? 'selected': ''}}> Agendamentos COMPLETOS</option>
                        <option value="RUNNING" {{$searchType=='EXECUTANDO'? 'selected': ''}}> Agendamentos EXECUTANDO</option>
                        <option value="ERROR" {{$searchType=='COM ERROS'? 'selected': ''}}> Agendamentos com ERROS</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <h2 id="SearchFilterType">{{$searchType}}</h2>
    </div>
</div>

@if(count ($schedules) == 0)
<div class="row">
    <div class="col-md-12">
        <h2 class="text-secondary">Sem resultados</h2>
    </div>
</div>
@endif

@foreach($schedules as $schedule)
<div class="row mt-4" style="box-shadow:2px 2px 15px rgba(0,0,0,0.2)">
    <div class="col-md-12">
        <div class="row">
            <div class=" col-md-4 d-flex flex-column">
                <strong>Semestre: {{$schedule->year . '.' . $schedule->period}}</strong>
                <small class="text-secondary">Criado em: {{date('d-m-Y h:i:s',strtotime($schedule->created_at)) }}</small>
                @if($schedule->status == 'COMPLETE')
                <small class="text-info">executado em: <b> {{(floor($schedule->{'update_time'}/3600)) }} </b> horas <b> {{(floor(($schedule->{'update_time'}%3600)/60))}} </b> minutos e <b>{{((($schedule->{'update_time'}%3600)%60))}} </b> segundos</small>
                @endif
            </div>
            <div class="col-md-4">
                <span class="text-primary">{{$schedule->{'num_new_data'} }} dado(s) criado(s)</span>
            </div>
            <div class="col-sm-3">
                @if($schedule->status == 'PENDING')
                <strong class="text-primary">AGENDADO</strong>
                @elseif($schedule->status == 'RUNNING')
                <strong class="text-success">EXECUTANDO</strong>
                @elseif($schedule->status == 'COMPLETE')
                <span><span class="text-secondary">Status: </span><strong class="text-primary">COMPLETO</strong></span>
                @elseif($schedule->status == 'ERROR')
                <strong class="text-danger">ERRO</strong>
                @endif
            </div>
            <div class="col-md-1 py-2">
                <form method="post" action="{{route('scheduling.delete')}}">
                    @csrf
                    @method('delete')
                    <input type="hidden" name="idSchedule" value="{{$schedule->id}}">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-danger btn-sm" type="submit">Excluir</button>
                    </div>
                </form>
            </div>
        </div>
        @if($schedule->{'error_description'})
        <div class="row">
            <div class="col-md-12 alert-danger">
                <span>{{$schedule->error_description}}</span>
            </div>
        </div>
        @endif
    </div>
</div>
@endforeach
<div class="row mt-3">
    <div class="col-md-12 d-flex justify-content-center">
        {{$schedules->links()}}
    </div>
</div>
@endsection

@section('scripts-bottom')
<script>
    let form = document.querySelector("#formSearchSchedules");

    function onSelectStatusSchedulesChange(event) {
        form.submit();
    }
</script>
@endsection