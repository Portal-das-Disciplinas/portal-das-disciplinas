@extends('layouts.app')

@section('robots')
noindex, follow
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')

<div class='banner' style='margin-bottom:-3rem'>
    <div class="vertical-center">
        <div class="container">
            <div class="login-card">
                <div class="custom-card-header">
                    <!--
                        <a type="cancel" class="btn btn-primary" href="{{ route('index') }}">
                            Inicio
                        </a>-->
                    <div class='login-img-container'>
                        <img src="{{ asset('img/imd-footer-2.png') }}" alt="Login Icon">
                    </div>
                    <h2 class='login-title'>Login</h2>
                    <p class='text-center'>Utilize suas credenciais para acessar o portal.</p>
                </div>
                    <form method="POST" class="w-100 py-4" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email"
                                class=""><strong>{{ __('E-Mail Address') }}</strong></label>
                            
                            <div class="login-input">
                                <input id="email" type="email" class=" smaller-p form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email"
                                autofocus placeholder="Usuário">
                            <i class="fas fa-user"></i>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            
                            <label for="password"
                                class=""><strong>{{ __('Password') }}</strong></label>
                            
                            <div class="login-input">
                                <input id="password" type="password"
                                class=" smaller-p form-control @error('password') is-invalid @enderror " name="password" required
                                autocomplete="current-password" placeholder='Senha'>
                            <i class="fas fa-key"></i>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                            </div>
                            

                          
                        </div>



                        <div class="">
                            <button type="submit" class="login-btn btn w-100">
                                {{ __('Login') }}
                            </button>

                            @if(Route::has('register'))
                                <a class="btn btn-primary" href="{{ route('register') }}">
                                    {{ __('Register') }}
                                </a>
                            @endif


                            @if(Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif

                        </div>
                    </form>
                    
                </div>
            </div>

        </div>
    </div>
</div>


@endsection
