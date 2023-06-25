@extends('layouts.app')

@section('title')
Painel de Administração - Portal das Disciplinas
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/classifications.css')}}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
@endsection

@section('description')
Painel de Administração
@endsection
@section('scripts-bottom')
<script type="text/javascript" src="{{asset('js/classifications.js')}}"></script>
@endsection
@section('content')

<div class="container">
    <div class="page-title">
        <h1>Painel de classificações</h1>
    </div>

    <div class="page-content" style='min-height: 60vh'>
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-3 mt-2 mb-2">
                <a name="createProfessor" class="btn btn-block btn-primary" href="{{ route('classificacoes.create') }}" role="button">Cadastrar classificação</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-center">
                <h3>Classificações</h3>
            </div>
            <!--<div class=" pb-0">
                 <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Tipo A</th>
                            <th scope="col">Tipo B</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($classifications as $i => $classification)
                        <tr>
                            <th scope="row">{{$i+1}}</th>
                            <td>{{ $classification->name }}</td>
                            <td>{{ $classification->type_a ?? '' }}</td>
                            <td>{{ $classification->type_b ?? '' }}</td>
                            <td>
                                <div class="form-group">
                                    <div class="d-flex">
                                        <a class="btn btn-primary mr-2" href="{{ route('classificacoes.edit', $classification->id) }}">Editar</a>
                                        <form action="{{route('classificacoes.destroy',$classification->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Deletar" class="btn btn-danger btn-block">
                                        </form>
                                    </div>
                                </div>
                                {{-- <div class="form-group">
                                        <form action="" method="POST">
                                            <input type="submit" value="Redefinir Senha" class="btn btn-primary btn-block">
                                        </form>
                                    </div> --}}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> -->
            <div class="">
                <div class="classifications_header container-fluid mb-2 pt-3 pb-3">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-xl-8">
                                <div class="containter-fluid">
                                    <div class="row">
                                        <div class="col-sm-1 d-flex align-items-center">
                                            #
                                        </div>
                                        <div class="col-sm-4 d-flex align-items-center">
                                            NOME
                                        </div>
                                        <div class="col-sm-4 d-flex align-items-center">
                                            TIPO A
                                        </div>
                                        <div class="col-sm-3 d-flex align-items-center">
                                            TIPO B
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-xl-4">

                            </div>
                        </div>
                    </div>
                </div>
                @foreach($classifications as $classification)
                <div id="{{'container'.$loop->index}}" class="classification_container container-fluid mb-2 pt-3 pb-3 pl-5 pr-5"> <!--canvas do cartão -->
                    <div id="{{'card'.$loop->index}}" class="classification_card  row pt-1 pb-1 d-flex align-items-center" draggable='true'><!--cartão -->
                        <div class="col-md-8">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-sm-1 align-items-center index_info">
                                        {{$loop->index}}
                                    </div>
                                    <div class="item-name col-sm-4 d-flex align-items-center justify-content-between">
                                        <strong class="info_sm text-secondary">nome:</strong>
                                        <span>{{$classification->name}} </span>
                                    </div>
                                    <div class="item-name col-sm-4 d-flex align-items-center justify-content-between">
                                        <strong class="info_sm text-secondary">tipo A:</strong>{{$classification->type_a}}
                                    </div>
                                    <div class="item-name col-sm-3 d-flex align-items-center justify-content-between">
                                        <strong class="info_sm text-secondary">tipo B</strong>{{$classification->type_b}}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 buttons-area">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-4 d-flex align-items-center justify-content-center">
                                        <a class="btn btn-primary btn-sm mr-2" href="{{ route('classificacoes.edit', $classification->id) }}">Editar</a>
                                    </div>
                                    <div class="col-4 d-flex align-items-center justify-content-center">
                                        <form action="{{route('classificacoes.destroy',$classification->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Deletar" class="btn btn-danger btn-sm">
                                        </form>
                                    </div>
                                    <div class="col-4">
                                        <div class="d-flex flex-row justify-content-center align-items-center">
                                            <button class="btn btn-success btn-sm ml-5 mr-1" onclick="moveUp('card'+'{{$loop->index}}')">
                                                <span class="material-symbols-outlined">
                                                    arrow_upward_alt
                                                </span>
                                            </button>
                                            <button class=" btn btn-primary btn-sm" onclick="moveDown('card'+'{{$loop->index}}')">
                                                <span class="material-symbols-outlined">
                                                    arrow_downward_alt</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>



</div>



@endsection