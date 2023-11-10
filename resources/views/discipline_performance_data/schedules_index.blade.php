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
    <div class="row mt-2" style="box-shadow:2px 2px 15px rgba(0,0,0,0.2)">
        <div class="col-md-12">
            <div class="row">
                <div class=" col-md-4 d-flex flex-column">
                    <span><b>Semestre: {{$schedule->year . '.' . $schedule->period}}</b></span>
                    <small class="text-secondary">Criado em: {{date('d-m-Y h:i:s',strtotime($schedule->created_at)) }}</small>
                    @if($schedule->status == 'COMPLETE')
                    <small class="text-info">executado em: <b> {{(floor($schedule->{'update_time'}/3600)) }} </b> horas <b> {{(floor(($schedule->{'update_time'}%3600)/60))}} </b> minutos e <b>{{((($schedule->{'update_time'}%3600)%60))}} </b> segundos</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <span class="text-primary">{{$schedule->{'num_new_data'} }} dado(s) criado(s)</span>
                </div>
                <div class="col-sm-2">
                    @if($schedule->status == 'PENDING')
                    <strong class="text-info">status: AGENDADO</strong>
                    @elseif($schedule->status == 'RUNNING')
                    <strong class="text-success">status: RODANDO</strong>
                    @elseif($schedule->status == 'COMPLETE')
                    <strong class="text-primary">status: COMPLETO</strong>
                    @elseif($schedule->status == 'ERROR')
                    <strong class="text-danger">status: ERRO</strong>
                    @endif
                </div>
            </div>
            @if($schedule->{'error_description'})
            <div class="row">
                <div class="col-md-12 alert-danger">
                    <span>{{$schedule->error_description}}</span>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-md-12 py-2">
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
        </div>
    </div>
    @endforeach
    <div class="row mt-3">
        <div class="col-md-12 d-flex justify-content-center">
            {{$schedules->links()}}
        </div>
    </div>

    <div id="modal-cadastro-agendamento" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cadastro de agendamento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form" action="{{route('scheduling.store')}}" method='POST'>
                        @csrf

                        <div class="form-group">
                            <label>Ano</label>
                            <select name="year" class="form-control">
                                @for($i= date('Y'); $i > date('Y')-10;$i--)
                                <option>{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Período</label>
                            <select name="period" class="form-control">
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <input id="updateIfExists" type="checkbox" name="updateIfExists">
                            <label for="updateIfExists" style="cursor:pointer">Atualizar dados existentes</label>
                        </div>
                        <div class="d-flex justify-content-end">
                            <input type="submit" class="btn btn-primary" value="cadastrar">
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-end">
                        <span class="text-secondary" style="cursor:pointer" data-dismiss="modal">Fechar</span>
                    </div>
                </div>
            </div>
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