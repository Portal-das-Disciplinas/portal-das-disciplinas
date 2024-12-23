@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-3">
            <div class="d-flex justify-content-center">
                <h1>Cadastro de níveis de curso</h1>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <button class="btn btn-primary">Cadastrar novo</button>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <table class="table table-striped">
                <thead>

                </thead>
                <tbody>
                    @foreach($courseLevels as $courseLevel)
                    <tr>
                        <td>{{$courseLevel->value}}</td>
                        <td>
                            <div class="d-flex justify-content-end"><button class="btn btn-danger">Apagar</button></div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


</div>


@endsection