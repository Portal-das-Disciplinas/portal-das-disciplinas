@extends('layouts.app')

@section('title')
Agendamentos
@endsection



@section('content')

<div class='container mt-4' style='min-height:100vh'>
    <div class="col-md-12">
        <div class="d-flex justify-content-center">
            <h1>Agendamento de pesquisa de índices de desempenho</h1>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-1 offset-11">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-cadastro-agendamento">Cadastrar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-start bg-primary">
                <form id="formSearchSchedules" class="w-100" method="GET">
                    <div class="form-row">
                        <select class="form-control">
                            <option value="PENDING"> Agendamentos PENDENTES</option>
                            <option value="COMPLETE"> Agendamentos COMPLETOS</option>
                            <option value="ERROR"> Agendamentos com ERROS</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div class="row mt-4">
        <div class="col-md-12">
            <h2 id="SearchFilterType">Buscas Agendadas</h2>
        </div>
    </div>
    @foreach($schedules as $schedule)
    <div class="row">
        <div class="col-md-12 mt-3 py-3" style="box-shadow:2px 2px 15px rgba(0,0,0,0.2)">
            <div class="d-flex flex-column">
                <div class="d-flex justify-content-between">
                    <div class="d-flex flex-column">
                        <span><b>Ano Letivo: {{$schedule->year}}</b></span>
                        <span><b>Período {{"0". $schedule->period}}</b></span>

                    </div>
                    <div>
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
                <div>
                    @if($schedule->{'error_description'})
                    <p class="text-secondary">{{$schedule->error_description}}</p>
                    @endif
                </div>
                <div>
                    <small class="text-secondary">Criado em: {{date('d-m-Y h:i:s',strtotime($schedule->created_at)) }}</small>
                </div>
                <div class="d-flex justify-content-end">
                    <form method="GET" action="{{route('scheduling.execute', $schedule->id)}}">
                        @csrf
                        <button class="btn btn-primary btn-sm" type="submit">Executar</button>
                    </form>
                    <form method="post" action="{{route('scheduling.delete')}}">
                        @csrf
                        @method('delete')
                        <input type="hidden" name="idSchedule" value="{{$schedule->id}}">
                        <button class="btn btn-danger btn-sm ml-2" type="submit">Excluir</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
    @endforeach

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

</div>

@endsection

@section('scripts-bottom')

@endsection