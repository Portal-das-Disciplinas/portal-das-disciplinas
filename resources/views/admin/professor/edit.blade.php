@extends('layouts.app')
@section('styles-head')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
@endsection

@section('content')


<div class="container remove-margin-bottom ">
  <div class='page-title'>
    <h1>Editar Perfil</h1>
  </div>
  <div class="row justify-content-center">
    <div class="profile-container col-12">
      <div class="card">
        @if (session('success'))
          <div class="alert alert-success border-left-success" role="alert">
              <span>{{ session('success') }}</span>
          </div>
        @endif
        <div class="card-header">

          <!-- <a class="btn btn-primary" href="{{route('home')}}">Inicio</a>-->
          <p class="text-center">Perfil</p>

        </div>

        <div class="card-body">
          <form action="{{ route('professores.update', $professor->id) }}" method="POST">
            @method('PUT')
            @csrf
            <div class="form-row">
              <div class="form-group col-12 pb-3">
                <label for="name">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"  id="name" name='name' value="{{$professor->name ?? ''}}">
                @error('name')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>



              <div class="form-group col-12 pb-3">
                <label for="email">Email </label>
                <input type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : ''}}"
                id="email"
                name='email'
                value="{{ $professor->user->email ?? ''}}"
                placeholder="Email do professor"
                autocomplete="off">
                @error('email')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>

              @if ($is_teacher)
                <div class="form-group col-12">
                    <label for="public_email" class="text-black">Email Público</label>
                    <input  type="text"
                            class="form-control {{ $errors->has('public-email') ? 'is-invalid' : ''}}"
                            name="public_email"
                            id="public_email"
                            value="{{$professor->public_email}}"
                            placeholder="Email público do professor (será informação pública no portal)"
                            autocomplete="off">
                    @error('public_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                    <div class="form-group col-md-6 pb-3">
                        <label for="rede_social1">Nome Rede Social 1 </label>
                        <input type="text" class="form-control {{ $errors->has('rede_social1') ? 'is-invalid' : ''}}" name='rede_social1' id="rede_social1"
                        value="{{$professor->rede_social1}}"
                        placeholder="rede social 1 do professor"
                        autocomplete="off">
                        @error('rede_social1')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="link_rsocial1">Link Rede Social 1 </label>
                        <input type="text" class="form-control {{ $errors->has('link_rsocial1') ? 'is-invalid' : ''}}" name='link_rsocial1' id="link_rsocial1"
                        value="{{$professor->link_rsocial1}}"
                        placeholder="rede social 1 do professor"
                        autocomplete="off">
                        @error('link_rsocial1')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="rede_social1">Nome Rede Social 2 </label>
                        <input type="text" class="form-control {{ $errors->has('rede_social2') ? 'is-invalid' : ''}}" name='rede_social2' id="rede_social2"
                        value="{{$professor->rede_social2}}"
                        placeholder="rede social 2 do professor"
                        autocomplete="off">
                        @error('rede_social2')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="link_rsocial2">Link Rede Social 2 </label>
                        <input type="text" class="form-control {{ $errors->has('link_rsocial2') ? 'is-invalid' : ''}}" name='link_rsocial2' id="link_rsocial2"
                        value="{{$professor->link_rsocial1}}"
                        placeholder="rede social 2 do professor"
                        autocomplete="off">
                        @error('link_rsocial2')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="rede_social1">Nome Rede Social 3 </label>
                        <input type="text" class="form-control {{ $errors->has('rede_social3') ? 'is-invalid' : ''}}" name='rede_social3' id="rede_social3"
                        value="{{$professor->rede_social3}}"
                        placeholder="rede social 3 do professor"
                        autocomplete="off">
                        @error('rede_social3')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="link_rsocial2">Link Rede Social 3 </label>
                        <input type="text" class="form-control {{ $errors->has('link_rsocial3') ? 'is-invalid' : ''}}" name='link_rsocial3' id="link_rsocial3"
                        value="{{$professor->link_rsocial3}}"
                        placeholder="rede social 3 do professor"
                        autocomplete="off">
                        @error('link_rsocial3')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="rede_social4">Nome Rede Social 4 </label>
                        <input type="text" class="form-control {{ $errors->has('rede_social4') ? 'is-invalid' : ''}}" name='rede_social4' id="rede_social4"
                        value="{{$professor->rede_social4}}"
                        placeholder="rede social 4 do professor"
                        autocomplete="off">
                        @error('rede_social')
                            <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>

                    <div class="form-group col-md-6 pb-3">
                        <label for="link_rsocial4">Link Rede Social 4 </label>
                        <input type="text" class="form-control {{ $errors->has('link_rsocial4') ? 'is-invalid' : ''}}" name='link_rsocial4' id="link_rsocial4"
                        value="{{$professor->link_rsocial4}}"
                        placeholder="rede social 4 do professor"
                        autocomplete="off">
                        @error('link_rsocial4')
                        <div class="invalid-feedback">{{$message}}</div>
                        @enderror
                    </div>
              @endif


              <div class="form-group col-md-6 pb-3">
                <label for="new_password">Nova senha </label>
                <input type="password" class="form-control @error('new_password') is-invalid @enderror" name='new_password' id="new_password" >
                @error('new_password')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>

              <div class="form-group col-md-6 pb-3">
                <label for="password_confirmation">Confirmação da senha </label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name='password_confirmation'>
                @error('password_confirmation')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>

              <div class="form-group col-12 pb-2">
                <label for="current_password">Senha atual</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name='current_password'>
                @error('current_password')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>

                <button type="submit" class="btn col-12">Atualizar</button>

            </div>



          </form>
        </div>
        {{-- {{dd($errors->all())}} --}}
      </div>
    </div>
  </div>


</div>
@endsection
