
<style>
  @import url("public/css/index.css"); /* Using a url */
  header {
      /*border-top: 2px solid #014C8C;*/
      width: 100vw;
      background-color: #fff;
  }

  .menu-container{
    width: 90vw;
    align-items: center;
    height: 100%;
  }

  nav{
    display:flex;
    align-items: center;
  }

  nav a {
      padding: 24px;
      text-decoration: none !important;
      font-size: 16px;
      font-weight: 600;
      color: rgb(32, 32, 32);
  }

  .login-btn {
      margin-left: 1.5rem;
      padding: 0.5rem 1rem;
      border-radius: 100px;
      outline: none;
      background-color: var(--main-bg-color);
      color: #fff;
      border: none;
  }

  .login-btn:hover {
      cursor: pointer;
      color: #fff
  }



  .logo-navbar {
      height: 40px;
      outline: none;

  }

  .container-menu-burger {
      --menu-size: 32px;
      position: relative;
      display: none;
      align-items: center;
      height: var(--menu-size);
      width: var(--menu-size);
  }

  .menu-burger,
  .menu-burger::before,
  .menu-burger::after {
      content: "";
      position: absolute;
      display: block;
      height: calc(var(--menu-size) / 10);
      width: var(--menu-size);
      background-color: #0c3f7c;;
      border-radius: 5px;
      transition: all .3s linear;
  }

  .menu-burger::before {
      transform: translateY(calc(var(--menu-size) / -3));
  }

  .menu-burger::after {
      transform: translateY(calc(var(--menu-size) / 3));
  }

  .user-icon{
    font-size: 2.4rem;
  }

  .user-icon:hover {
      color: #014C8C;
      cursor: pointer;
  }


  .menu-section.on{
    position: fixed;
    height: 100vh;
    background-color: #0c3f7c;
    width: 70vw;
    right: 0;
    top: 0;
    z-index: 10;
  }

  .menu-section.on .container-menu-burger{
    position: fixed;
    top: 16px;
    right: 0;
    margin-right: 5%;
  }

  .menu-section.on .menu-burger,
  .menu-section.on .menu-burger::before,
  .menu-section.on .menu-burger::after {
    background-color: white;
  }

  .menu-section.on nav{
    display: block;
    text-align: center;
  }
  .menu-section.on nav a{
    display: block;
    text-align: left;
    margin: 0 0;
    color: white;
    
  }
  .menu-section.on nav .login-btn{
    margin: 8px 16px; 
    width: 90%;
    text-align: center;
  }
  .menu-section.on nav .dropdown{
    margin: 10px 6px; 
    text-align: center;
    color: white;
  }

  @media only screen and (max-width:768px){
    header{
      height: 64px;
    }
    header nav{
      display: none;
    }

    header .container-menu-burger{
      display: flex !important
    }

    .menu-container{
      padding: 0;
    }
  }

  </style>

  <header class="sticky-top shadow">
      <div class="menu-container container d-flex mt-0 justify-content-between align-items-center">

          <a class="navbar-brand" href="{{ route('index') }}">
              <img src="{{ asset('img/new-imd-logo.svg') }}" class='logo-navbar' alt="Logo do IMD">
          </a>
          <div class="menu-section">
              <div class="container-menu-burger">
                  <div class="menu-burger"></div>
              </div>




              <nav>
                  <a href="{{ route("index") }}">Inicio</a>
                  <a href="{{ route('information') }}">Sobre & Colabore</a>
                  @guest
                      @if(Route::has('login'))
                          <a href="{{ route('login') }}"
                              class='login-btn'>{{ __('Acesso Portal') }}</a>
                      @endif
                  @else
                  @endguest



                  <label class="label-btn d-none">
                      <i class="fas fa-bars" id='navbar_btn' onclick="move(true)"></i>
                  </label>
                  @auth
                      <div class="dropdown show">
                          <div class="user-icon d-flex align-items-center ml-3" data-toggle="dropdown">
                              <i class="far fa-user-circle mr-3"></i>
                              <i class="fas fa-caret-down"></i>
                          </div>
                          <div class="user-dropdown dropdown-menu">
                              <h6 class="dropdown-header">Olá,
                                  <span>{{ Str::words( Auth::user()->name, 2, '' ) }}</span>
                                  <h6>
                                      <div class="dropdown-divider"></div>

                                      <a href="{{ route('profile') }}">
                                          <div class="dropdown-item py-3"> <i class="far fa-user mr-2"></i> Meu perfil
                                          </div>
                                      </a>
                                      <a href="{{ route("disciplinas.create") }}">
                                          <div class="dropdown-item py-3"> <i class="fas fa-book mr-2"></i></i>
                                              Cadastrar
                                              disciplina </div>
                                      </a>
                                      @if(auth()->user()->is_admin)
                                          <a href="{{ route("professores.index") }}">
                                              <div class="dropdown-item py-3"> <i
                                                      class="fas fa-users-cog mr-2"></i>Painel
                                                  de Administração</div>
                                          </a>
                                          <a href="{{ route("classificacoes.index") }}">
                                              <div class="dropdown-item py-3"> <i class="fas fa-star mr-2 "></i>Painel
                                                  de
                                                  Classificações</div>
                                          </a>
                                      @endif

                                      <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();" class=''>
                                          <div class="dropdown-item py-3"><i
                                                  class="fas fa-sign-out-alt mr-2"></i></i>{{ __('Logout') }}
                                          </div>


                                      </a>
                                      <form id="logout-form" action="{{ route('logout') }}"
                                          method="POST">
                                          @csrf
                                      </form>

                          </div>
                      </div>
                  @endauth
              </nav>
          </div>
      </div>

  </header>


<script>
  let show = false
  const menuSection = document.querySelector('.menu-section');
  const burgerContainer = document.querySelector('.container-menu-burger')

  burgerContainer.addEventListener('click', () => {
    menuSection.classList.toggle('on');
    show = !show
  })
</script>