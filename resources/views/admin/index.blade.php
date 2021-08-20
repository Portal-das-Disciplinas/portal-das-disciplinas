@extends('layouts.app')

@section('title')
Painel de Administração - Portal das Disciplinas
@endsection

@section('description')
Painel de Administração
@endsection

@section('content')

<div class="row">
    <div class="col-12 col-sm-6 col-lg-3 mt-5 mb-2">
        <a name="createProfessor" class="btn btn-outline-light btn-block"
           href="{{ route("professores.create") }}" role="button">Cadastrar professor</a>
    </div>
</div>

<div class="card">
    <h4 class="text-center m-4">Professores</h4>
    <div class="card-body">
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


@endsection
