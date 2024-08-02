@extends('layouts.app')
@section('content')

<div class="container mt-2" style="min-height: 100vh;">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <h1>Acessos ao Portal</h1>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card px-2">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <strong class="d-flex justify-content-center text-secondary">Dados de todo o período</strong>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <span class="text-secondary">Total de acessos: <strong>{{$totalAccess}}</strong></span>
                    </div>
                    <div class="col-md-4">
                        <span class="text-secondary">Total de acessos únicos: <strong>{{$totalDistinctAccess}}</strong></span>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex flex-column align-items-center">
                            <span class="text-secondary">URL(s) mais acessada(s)</span>
                            @if(isset($pathMoreAccessed))
                            @foreach($pathMoreAccessed as $path)
                            <span class="text-secondary">{{$path}}</span>
                            @endforeach
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row py-4">
        <div class="col-md-12">
            <hr>
            <hr class="mt-1">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h3>Dados por período</h3>
        </div>
    </div>
    <div class="row mt-3 ">
        <div class="col-md-12">
            <form class="form-inline" method="get" action="{{route('portal_access_info.index')}}">
                <div class="form-group mr-5">
                    <label for="initial-date" class="mr-2">Data inicial</label>
                    <input id="initial-date" name="initial-date" class="form-control" type="date" value="{{$oldInitialDate}}">
                </div>
                <div class="form-group mr-5">
                    <label for="final-date" class="mr-2">Data Final</label>
                    <input id="final-date" name="final-date" class="form-control" type="date" value="{{$oldFinalDate}}">
                </div>
                <button class="btn btn-primary">Pesquisar</button>
            </form>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="d-flex justify-content-center">
                <div class="d-flex flex-column align-items-center card p-4 mr-3" style="box-shadow: 2px 2px 10px rgba(0,0,0,0.2);">
                    <h3>TOTAL DE ACESSOS</h3>
                    <h3>{{$totalAccessPeriod}}</h3>
                </div>
                <div class="d-flex flex-column align-items-center card p-4 mr-3" style="box-shadow: 2px 2px 10px rgba(0,0,0,0.2);">
                    <h3>TOTAL DE ACESSOS ÚNICOS</h3>
                    <h2 class="text-primary">{{$totalDistinctAccessPeriod}}</h2>
                </div>
                <div class="d-flex flex-column align-items-center card p-4" style="box-shadow: 2px 2px 10px rgba(0,0,0,0.2);">
                    <h3>URL(s) MAIS ACESSADA(s)</h3>
                    @if(isset($pathMoreAccessedPeriod))
                    @foreach($pathMoreAccessedPeriod as $path)
                    <i>{{$path}}</i>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection