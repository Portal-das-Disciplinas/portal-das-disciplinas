<div id="sidebar">

    <div class="d-flex align-items-center">
        <label class="">
            <i class="fas fa-bars navbar-icon" id='sidebar_btn-active' onclick="move(false)"></i>
        </label>
    </div>

    <div class="text-center">
        @auth
            <div class="user">
                {{-- <img src="img/img1.jpg" alt="" class="sidebar-image_profile"> --}}
                <p class="name_user">{{ Str::words( Auth::user()->name, 2, '' ) }}</p>
            </div>
        @endauth

        <ul class="sidebar-list">

            <li class="sidebar-item">
                <a class="list-links" href="{{route("index")}}">Inicio</a>
            </li>

            @auth
                <li class="sidebar-item">
                    <a href="{{route('profile')}}" class="list-links">Meu perfil</a>
                </li>
                {{-- <li class="sidebar-item">
                    <a href="{{route("mydisciplines")}}" class="list-links">Minhas disciplinas</a>
                </li> --}}
                @if (auth()->user()->is_admin)
                <li class="sidebar-item">
                        <a name="adminPannel" class="list-links"
                           href="{{ route("professores.index") }}" role="button">Painel de Administração</a>
                </li>
                @endif
            @endauth

            <li class="sidebar-item">
                <a class="list-links" href="{{route('information')}}">Sobre</a>
            </li>
            <li class="sidebar-item">
                <a class="list-links" href="{{route('collaborate')}}">Como colaborar</a>
            </li>

            {{-- <li class="sidebar-item">
                <a class="list-links" href="#">FAQ</a>
            </li> --}}
        </ul>
    </div>
</div>
