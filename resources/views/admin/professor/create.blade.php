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
        <h1>Cadastrar professor</h1>
    </div>
    <div style='min-height: 60vh;'>
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
                            value = "{{old('name')}}"
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
                            value="{{old('email')}}"
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
                            class="form-control {{ $errors->has('public_email') ? 'is-invalid' : ''}}"
                            name="public_email"
                            id="public_email"
                            placeholder="Email público do professor (será informação pública no portal)"
                            value="{{old('public_email')}}"
                            autocomplete="off">
                        @error('public_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="rede_social1" class="text-black">Rede Social 1</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('rede_social1') ? 'is-invalid' : ''}}"
                            name="rede_social1"
                            id="rede_social1"
                            placeholder="Nome da Rede Social 1"
                            value="{{old('rede_social1')}}"
                            autocomplete="off">
                    @error('rede_social1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="link_rsocial1" class="text-black">Link da Rede Social 1</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('link_rsocial1') ? 'is-invalid' : ''}}"
                            name="link_rsocial1"
                            id="link_rsocial1"
                            placeholder="Link da Rede social 1"
                            value="{{old('link_rsocial1')}}"
                            autocomplete="off">
                    @error('link_rsocial1')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                    <label for="rede_social2" class="text-black">Rede Social 2</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('rede_social2') ? 'is-invalid' : ''}}"
                            name="rede_social2"
                            id="rede_social2"
                            placeholder="Nome da Rede Social 2"
                            value="{{old('rede_social2')}}"
                            autocomplete="off">
                    @error('rede_social2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="link_rsocial2" class="text-black">Link da Rede Social 2</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('link_rsocial2') ? 'is-invalid' : ''}}"
                            name="link_rsocial2"
                            id="link_rsocial2"
                            placeholder="Link da Rede social 2"
                            value="{{old('link_rsocial2')}}"
                            autocomplete="off">
                    @error('link_rsocial2')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                    <label for="rede_social3" class="text-black">Rede Social 3</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('rede_social3') ? 'is-invalid' : ''}}"
                            name="rede_social3"
                            id="rede_social3"
                            placeholder="Nome da Rede Social 3"
                            value="{{old('rede_social3')}}"
                            autocomplete="off">
                    @error('rede_social3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="link_rsocial3" class="text-black">Link da Rede Social 3</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('link_rsocial3') ? 'is-invalid' : ''}}"
                            name="link_rsocial3"
                            id="link_rsocial3"
                            placeholder="Link da Rede social 3"
                            value="{{old('link_rsocial3')}}"
                            autocomplete="off">
                    @error('link_rsocial3')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="form-row">
            <div class="form-group col-md-6">
                    <label for="rede_social4" class="text-black">Rede Social 4</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('rede_social4') ? 'is-invalid' : ''}}"
                            name="rede_social4"
                            id="rede_social4"
                            placeholder="Nome da Rede Social 4"
                            value="{{old('rede_social4')}}"
                            autocomplete="off">
                    @error('rede_social4')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-md-6">
                    <label for="link_rsocial4" class="text-black">Link da Rede Social 4</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('link_rsocial4') ? 'is-invalid' : ''}}"
                            name="link_rsocial4"
                            id="link_rsocial4"
                            placeholder="Link da Rede social 4"
                            value="{{old('link_rsocial4')}}"
                            autocomplete="off">
                    @error('link_rsocial4')
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
            <div class="form-row justify-content-center">
                <div class="form-group col-md-6 mt-2">
                    <input type="submit" value="Cadastrar" class="btn btn-block btn-primary w-100">
                </div>
            </div>
         </form>
    </div>
    
</div>
@endsection
