
<style>
  .user-icon:hover{
    color:#014C8C;
    cursor:pointer;
  }
</style>

<header class="w-100 bg-light sticky-top shadow " style='border-top: 8px solid #014C8C'>
    
    <nav class="container mt-0">
      <div class="row">

        <div class=" d-flex align-items-center justify-content-between w-100" id="navbarNav" style="height:80px">
          <a class="navbar-brand mr-0" href="{{route('index')}}">
            <img src="{{asset('img/imdLogo.png')}}"  class='logo-navbar' alt="Logo do IMD">
          </a>
          {{-- <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#">Sobre</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">FAQ</a>
            </li>
          </ul> --}}

          <div class="ml-auto d-flex align-items-center nav-list">
            
            <a href="{{route("index")}}" class='nav-link grey-link'>Inicio</a>
            <a href="{{route('information')}}" class='nav-link grey-link'>Sobre & Colabore</a>
            
            

            @guest
                @if (Route::has('login'))
                        <a class="btn btn-primary ml-3" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                @endif
            @else   
            @endguest
          </div>

  

          <label class="label-btn d-none">
            <i class="fas fa-bars" id='navbar_btn' onclick="move(true)"></i>
          </label>
          @auth
            <div class="dropdown show">
              <div class="user-icon d-flex align-items-center ml-3" data-toggle="dropdown">
                <i class="far fa-user-circle mr-2" style='font-size: 1.5rem'></i>
                <i class="fas fa-caret-down"></i>
              </div>
              <div class="user-dropdown dropdown-menu">
                <h6 class="dropdown-header">Olá, <span>{{ Str::words( Auth::user()->name, 2, '' ) }}</span><h6>
                <div class="dropdown-divider"></div>
                
                <a href="{{route('profile')}}"><div class="dropdown-item py-3"> <i class="far fa-user mr-2"></i>  Meu perfil</div></a>
                <a href="{{ route("disciplinas.create") }}"><div class="dropdown-item py-3"> <i class="fas fa-book mr-2"></i></i>  Cadastrar disciplina </div></a>
                @if (auth()->user()->is_admin)
                  <a href="{{ route("professores.index") }}"><div class="dropdown-item py-3"> <i class="fas fa-users-cog mr-2"></i>Painel de Administração</div></a>
                  <a href="{{ route("classificacoes.index") }}"><div class="dropdown-item py-3"> <i class="fas fa-star mr-2 "></i>Painel de Classificações</div></a>
                @endif

                <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();" class=''>
                                      <div class="dropdown-item py-3"><i class="fas fa-sign-out-alt mr-2"></i></i>{{ __('Logout') }}</div>
                        
                        
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" >
                  @csrf
                </form>

              </div>
            </div>
          @endauth
         
          
        </div>
        
      </div>
      




    </nav>
</header>


