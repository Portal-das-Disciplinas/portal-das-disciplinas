@extends('layouts.app')

@section('title')
Agendamentos
@endsection
@section('content')
<div class="container" style="min-height:70vh">
    <div class="row mb-3 mt-5 py-3" style="border-radius:5px;box-shadow:2px 2px 10px rgba(0,0,0,0.2)">
        <div class="col-md-9">
            <a class="nav-link d-inline-block mr-2" href="{{route('scheduling.index')}}">Agendamentos</a>
            <a class="nav-link d-inline-block mr-2" href="{{route('performance.index')}}">Ver dados de performance obtidos</a>
            <a class="nav-link d-inline-block" href="{{route('semester_performance_data')}}">Ver semestres pesquisados</a>
        </div>
        <div class="col-sm-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-cadastro-agendamento">Cadastrar Agendamento</button>
        </div>


    </div>
    @yield('content2')
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
                        <div class="row">
                            <div class="col-md-6">
                                <label>Ano inicial</label>
                                <select name="yearStart" class="form-control">
                                    @for($i= date('Y'); $i > 2000; $i--)
                                    <option>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Período inicial</label>
                                <select name="periodStart" class="form-control" style="text-align: end;">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Ano final</label>
                                <select name="yearEnd" class="form-control">
                                    @for($i= date('Y'); $i > 2000; $i--)
                                    <option>{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label>Período final</label>
                                <select name="periodEnd" class="form-control" style="text-align: end;">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <input id="updateIfExists" type="checkbox" name="updateIfExists">
                        <label for="updateIfExists" class="text-primary" style="cursor:pointer; user-select:none">Atualizar dados existentes</label>
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