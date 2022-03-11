
<style>
  @import url("public/css/index.css"); /* Using a url */
  header{
    /*border-top: 2px solid #014C8C;*/
    width: 100vw;
    background-color: #fff;
  }

  nav a{
    padding: 24px;
    text-decoration: none !important;
    font-size: 16px;
    font-weight: 600;
    color: rgb(32, 32, 32)
  }

  .login-btn{
    margin-left:1.5rem;
    padding: 0.5rem 1rem;
    border-radius: 100px;
    outline: none;
    background-color: var(--main-bg-color);
    color: #fff;
    border: none;
  }

  .login-btn:hover{
    cursor: pointer;
    color: #fff
  }

  .user-icon:hover{
    color:#014C8C;
    cursor:pointer;
  }


  .logo-navbar{
    height: 40px;
    outline:none;
    
  }
  
</style>

<header class="sticky-top shadow">
  <div class="container d-flex mt-0 justify-content-between align-items-center">
    <a class="navbar-brand" href="{{route('index')}}">
      <img src="{{asset('img/new-imd-logo.svg')}}"  class='logo-navbar' alt="Logo do IMD">
    </a>
    <nav class='d-flex align-items-center'>
      <a href="{{route("index")}}">Inicio</a>
      <a href="{{route('information')}}">Sobre & Colabore</a>
      @guest
          @if (Route::has('login'))
                  <a href="{{ route('login') }}" class='login-btn'>{{ __('Acesso Portal') }}</a>
          @endif
      @else   
      @endguest



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
     
      
    
    
  
</nav>
  </div>
    
</header>


