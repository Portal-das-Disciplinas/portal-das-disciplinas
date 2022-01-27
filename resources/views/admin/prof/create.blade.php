@extends('layouts.app')

@section('title')
Cadastro de Professor - Portal das Disciplinas
@endsection
@section('description')
Cadastro de Professor
@endsection

@section('content')

<div class="container">
    <h4 class="text-center m-4">Cadastrar professor</h4>
     <form action="{{route('professores.store')}}" method="post">
        @csrf
        <div class="form-row">
            <div class="form-group col-12">
                <label for="name" class="text-black">Nome</label>
                <input  type="text"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}"
                        name="name"
                        id="name"
                        placeholder="Nome do Professor"
                        autocomplete="off">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <label for="email" class="text-black">Email</label>
                <input  type="text"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : ''}}"
                        name="email"
                        id="email"
                        placeholder="Email do Professor (Utilizado no Login)"
                        autocomplete="off">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-12">
                <label for="public_email" class="text-black">Email Público</label>
                <input  type="text"
                        class="form-control {{ $errors->has('public-email') ? 'is-invalid' : ''}}"
                        name="public_email"
                        id="public_email"
                        placeholder="Email público do professor (será informação pública no portal)"
                        autocomplete="off">
                    @error('public_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="password" class="text-black">Senha</label>
                <input  type="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : ''}}"
                        name="password"
                        id="password"
                        placeholder="Senha"
                        autocomplete="off">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="password_confirmation" class="text-black">Confirmar senha</label>
                <input  type="password"
                        class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : ''}}"
                        name="password_confirmation"
                        id="password_confirmation"
                        placeholder="Confirme a senha"
                        autocomplete="off">
                @error('password_confirmation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4 mt-2">
                <input type="submit" value="Cadastrar" class="btn btn-block btn-primary w-50">
            </div>
        </div>
     </form>
</div>
@endsection
