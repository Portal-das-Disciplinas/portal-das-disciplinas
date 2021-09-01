@extends('layouts.app')

@section('robots')
noindex, follow
@endsection

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection

@section('content')

<div class='discipline-banner' style='margin-bottom:-3rem' >
    <div class="vertical-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card card-auth">
                        <div class="card-header">
                            <a type="cancel" class="btn btn-primary" href="{{ route('index') }}">
                                Inicio
                            </a>

                            <div class="text-center">
                                <h2>Realizar login</h2>
                            </div>
                            
                        </div>

                        <div class="card-body">
                            <form method="POST" class="py-4" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="row justify-content-center mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class=""><strong>{{ __('E-Mail Address') }}</strong></label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>
                                </div>

                                <div class="row justify-content-center mb-3">
                                    <div class="col-md-6">
                                        <label for="password" class=""><strong>{{ __('Password') }}</strong></label>
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror " name="password" required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                    </div>
                                </div>
                                
                                <div class="row mb-0 justify-content-center">
                                    <div class="col-md-6 ">
                                        <button type="submit" class="btn btn-primary w-100">
                                            {{ __('Login') }}
                                        </button>

                                        @if (Route::has('register'))
                                            <a class="btn btn-primary" href="{{ route('register') }}">
                                                {{ __('Register') }}
                                            </a>
                                        @endif
                                        

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                {{ __('Forgot Your Password?') }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
