@extends('layouts.app')

@section('styles-head')
<link rel="stylesheet" href="{{asset('css/about.css')}}">
@endsection

@section('content')
<div class="container mt-5" style="min-height:100vh">
    <div class="row">
        <div class="col-md-3">
            <div class="d-flex flex-column align-items-center mb-3">
                @if(isset($collaborator->urlPhoto))
                <img class="clip-path regular-image" src="{{'/storage/' . $collaborator->urlPhoto}}">
                @else
                <img class="clip-path regular-image" src="{{asset('img/profiles_img/user2.png')}}">
                @endif
                <strong> {{$collaboratorName}}</strong>
                <h4 class="text-secondary py-2" style="text-align:center; line-height:0.9">{{$collaborator->role}}</h4>
                @if(isset($collaborator->github) && $collaborator->github != '')
                <a href="{{$collaborator->github}}" target="_blank" class="d-flex align-items-center"><img src="/img/github-mark.svg" width="20px">Github</a>
                @endif
            </div>

        </div>

        <div class="col-md-9">
            <div class="row">
                <div class="col-md-12 mb-3">

                    <h3 class="text-secondary" style="text-align:center">Produções do Colaborador no Portal das Disciplinas</h3>
                </div>
            </div>
            @foreach($collaboratorProductions as $production)
            <div class="card p-2 mb-3" style="box-shadow: 2px 2px 5px rgba(0,0,0,0.1)">

                <p class="smaller-p"><b>{{$production->brief}}</b></p>
                @if($production->details)
                <a class="smaller-p" data-toggle="collapse" href="{{'#collapseDetails' . $production->id}}" role="button" aria-expanded="false" aria-controls="{{'collapseDetails' . $production->id}}">
                    Detalhes
                </a>
                @endif

                <div class="collapse" id="{{'collapseDetails' . $production->id}}">
                    <div class="card card-body">
                        <p class="smaller-p text-secondary">{{$production->details}}</p>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection