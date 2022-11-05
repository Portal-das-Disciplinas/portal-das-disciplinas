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
        <h1>Painel de administração</h1>
    </div>
    <div class="register-teacher-container" style='min-height: 60vh'>
        <div class="row">
            <div class="col-12 col-sm-6 col-lg-3 mt-2 mb-2">
                 <a name="createProfessor" class="btn btn-block btn-primary"
                   href="{{ route('professores.create') }}" role="button">Cadastrar professor</a>
            </div>
        </div>

        <div style='background-color: #fff; border-radius: 8px;'>
            <div class="table-responsive card-body pb-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nome</th>
                            <th scope="col">Email</th>
                            <th scope="col">Email Público</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($professors as $i => $professor)
                            <tr>
                                <th scope="row">{{$i+1}}</th>
                                <td>{{$professor->name}}</td>
                                <td>{{$professor->user->email}}</td>
                                <td>{{$professor->public_email}}</td>
                                <td>
                                    <div class="form-group">
                                        <form action="{{route('professores.destroy',$professor->id)}}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="submit" value="Deletar" class="btn btn-danger btn-block">
                                        </form>
                                    </div>
                                    <form action="{{route('professores.edit', $professor->id)}}" method="GET">
                                            @csrf
                                            @method('GET')
                                            <input type="submit" value="Atualizar" class="btn btn-block">
                                    </form>
                                    <!-- {{-- <div class="form-group">
                                        <form action="" method="POST">
                                            <input type="submit" value="Redefinir Senha" class="btn btn-primary btn-block">
                                        </form>
                                    </div> --}} -->
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
