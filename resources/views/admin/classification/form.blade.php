@extends('layouts.app')

@section('title')
Cadastro de Professor - Portal das Disciplinas
@endsection
@section('description')
Cadastro de Professor
@endsection

@section('content')

<div class="container">
    <div class="page-title">
        <h1>Cadastrar classificação</h1>
    </div>
    <h4 class="text-center m-4"> {{ isset($edit) ? 'Atualizar' : 'Cadastrar'}} Classificação</h4>
     <form action="{{ isset($edit) ? route('classificacoes.update', $id) :  route('classificacoes.store') }}" method="post">
        @csrf
        @if(isset($edit) && $edit)
            @method('PUT')
        @endif
        <div class="form-row">
            <div class="form-group col-6">
                <label for="name" class="text-black">Nome</label>
                <input  type="text"
                        class="form-control {{ $errors->has('name') ? 'is-invalid' : ''}}"
                        name="name"
                        id="name"
                        placeholder="Nome da classificação"
                        autocomplete="off"
                        value="{{ $classification->name ?? '' }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="type_a" class="text-black">Tipo A</label>
                <input  type="text"
                        class="form-control {{ $errors->has('type_a') ? 'is-invalid' : ''}}"
                        name="type_a"
                        id="type_a"
                        placeholder="Primeiro tipo da classificação"
                        autocomplete="off"
                        value="{{ $classification->type_a ?? '' }}">
                    @error('type_a')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
            </div>
        </div>


        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="type_b" class="text-black">Tipo B</label>
                <input  type="text"
                        class="form-control {{ $errors->has('type_b') ? 'is-invalid' : ''}}"
                        name="type_b"
                        id="type_b"
                        placeholder="Segundo tipo da classificação"
                        autocomplete="off"
                        value="{{ $classification->type_b ?? '' }}">
                @error('type_b')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="description" class="text-black">Descrição</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : ''}} " id="description" name="description"  rows="5"> {{ $classification->description ?? '' }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4 mt-2">
                <input type="submit" value="{{ isset($edit) ? 'Atualizar' : 'Cadastrar'}}" class="btn btn-block btn-primary w-50">
            </div>
        </div>
     </form>
</div>
@endsection
