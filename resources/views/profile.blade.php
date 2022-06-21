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
          <form action="{{route('updateUser')}}" method="POST">
            @csrf
            <div class="form-row">
              <div class="form-group col-12 pb-3">
                <label for="name">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"  id="name" name='name' value="{{Auth::user()->name}}">
                @error('name')
                  <div class="invalid-feedback">{{$message}}</div>
                @enderror
              </div>
    
              <div class="form-group col-12 pb-3">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name='email' value="{{Auth::user()->email}}" >
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
                            value="{{Auth::user()->professor->public_email}}"
                            placeholder="Email público do professor (será informação pública no portal)"
                            autocomplete="off">
                    @error('public_email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group col-12">
                  <label for="public_link" class="text-black">Link público</label>
                  <input  type="url"
                          class="form-control {{ $errors->has('public-link') ? 'is-invalid' : ''}}"
                          name="public_link"
                          id="public_link"
                          value="{{Auth::user()->professor->public_link}}"
                          placeholder="Link pessoal para algum perfil do professor (será informação pública no portal)"
                          autocomplete="off">
                      @error('public_link')
                          <div class="invalid-feedback">{{ $message }}</div>
                      @enderror
                </div>
              @endif
            
    
              <div class="form-group col-md-6 pb-3">
                <label for="new_password">Nova senha</label>
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