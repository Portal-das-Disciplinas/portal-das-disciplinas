<header class="w-100 bg-light ">
    
    <nav class="container mt-0">
      <div class="row">

        <div class="d-flex align-items-center justify-content-between w-100" id="navbarNav" style="height:80px">
          <a class="navbar-brand mr-0" href="{{route('index')}}">
            <img src="{{asset('img/imdLogo.png')}}"  class='logo-navbar'alt="Logo do IMD">
          </a>
          {{-- <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#">Sobre</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">FAQ</a>
            </li>
          </ul> --}}

          <div class="ml-auto d-flex align-items-center">
            
            <a href="{{route("index")}}" class='nav-link'>Inicio</a>
            <a href="{{route('information')}}" class='nav-link'>Sobre & Colabore</a>
            
            

            @guest
                @if (Route::has('login'))
                    
                        <a class="btn btn-outline-primary" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                    
                @endif
            @else
                
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();" class='nav-link'>
                        {{ __('Logout') }}
                        
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                
            @endguest
          </div>
          <label class="label-btn">
            <i class="fas fa-bars" id='navbar_btn' onclick="move(true)"></i>
          </label>
          @auth
            <div class="dropdown show">
              <div class="d-flex align-items-center" data-toggle="dropdown">
                <i class="far fa-user-circle fa-lg mr-2"></i>
                <i class="fas fa-caret-down"></i>
              </div>
              <div class="dropdown-menu">
                <h6 class="dropdown-header">Olá, <span>{{ Str::words( Auth::user()->name, 2, '' ) }}</span><h6>
                <div class="dropdown-divider"></div>
                <a href="{{route('profile')}}"><div class="dropdown-item py-3"> <i class="far fa-user mr-2"></i>  Meu perfil</div></a>
                <a href="{{route('profile')}}"><div class="dropdown-item py-3"> <i class="fas fa-users-cog mr-2"></i>Painel de Administração</div></a>
              </div>
            </div>
          @endauth
         
          
        </div>
        
      </div>
      




    </nav>
</header>
