@extends('layouts.app')

@section('content')    

  <div class="card">
    @if (session('success'))
      <div class="alert alert-success border-left-success" role="alert">
          <span>{{ session('success') }}</span>
      </div>
    @endif
    <div class="card-header">
      <a class="btn btn-primary" href="{{route('home')}}">Inicio</a>
      <p class="h4 text-center">Perfil</p>
        
    </div>
    
    <div class="card-body">
      <form action="{{route('updateUser')}}" method="POST">
        @csrf
        <div class="form-row">
          <div class="form-group col-12">
            <label for="name">Nome</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"  id="name" name='name' value="{{Auth::user()->name}}">
            @error('name')
              <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>

          <div class="form-group col-12">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name='email' value="{{Auth::user()->email}}" >
            @error('email')
              <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>

          <div class="form-group col-md-6">
            <label for="new_password">Nova senha</label>
            <input type="password" class="form-control @error('new_password') is-invalid @enderror" name='new_password' id="new_password" >
            @error('new_password')
              <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>

          <div class="form-group col-md-6">
            <label for="password_confirmation">Confirmação da senha </label>
            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name='password_confirmation'>
            @error('password_confirmation')
              <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>

          <div class="form-group col-6">
            <label for="current_password">Senha atual</label>
            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name='current_password'>
            @error('current_password')
              <div class="invalid-feedback">{{$message}}</div>
            @enderror
          </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Atualizar</button>
      </form>
    </div>
    {{-- {{dd($errors->all())}} --}}
  </div>
@endsection