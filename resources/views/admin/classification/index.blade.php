@extends('layouts.app')

@section('title')
Painel de Administração - Portal das Disciplinas
@endsection

@section('description')
Painel de Administração
@endsection

@section('content')

<div class="container">
    <div class="page-title">
        <h1>Painel de classificações</h1>
    </div>

    <div class="page-content" style='min-height: 60vh'>
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-3 mt-2 mb-2">
                <a name="createProfessor" class="btn btn-block btn-primary"
                   href="{{ route("classificacoes.create") }}" role="button">Cadastrar classificação</a>
            </div>
        </div>

        <div class="card">
            <div class="card-header text-center">
                <h3>Classificações</h3>
            </div>
            <div class="card-body pb-0">
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
            </div>
        </div>
    </div>
    
    
    
</div>



@endsection
