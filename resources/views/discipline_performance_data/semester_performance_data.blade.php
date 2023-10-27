@extends('layouts.app')

@section('title')
Semestre com dados de índices de rendimento
@endsection

@section('content')
<div class="conteiner" style="min-height:70vh">
    <div class="row my-3">
        <div class="col-md-12 d-flex justify-content-center">
            <h1>Semestres com dados de índices de desempenho</h1>
        </div>
    </div>
    @if(count($semesterPerformanceData) > 0)
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th scope="col">Semestre</th>
                        <th scope="col">Turmas com dados</th>
                        <th scope="col">Contem erros</th>
                    </tr>
                </thead>
                @foreach($semesterPerformanceData as $data)
                <tr class="{{$data->{'has_errors'} ? 'table-danger' : ''}}">
                    <td scope="row">{{$data->year . "." . $data->period}}</td>
                    <td scope="row">{{$data->{'data_amount'} }} turma(s)</td>
                    @if($data->{'has_errors'})
                    <td scope="row">SIM</td>
                    @else
                    <td scope="row">NAO</td>
                    @endif
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
</div>

@endsection