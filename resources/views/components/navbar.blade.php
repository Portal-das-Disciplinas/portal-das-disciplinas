
<style>
  .user-icon:hover{
    color:#014C8C;
    cursor:pointer;
  }
</style>

<header class="w-100 bg-light sticky-top shadow " style='border-top: 8px solid #014C8C'>
    
    <nav class="container mt-0">
      <div class="row px-3">

        <div class=" d-flex align-items-center justify-content-between w-100" id="navbarNav" style="height:80px">
          <a class="navbar-brand mr-0" href="{{route('index')}}">
            <img src="{{asset('img/imdLogo.png')}}"  class='logo-navbar' alt="Logo do IMD">

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
                              class='access-btn'>{{ __('Acesso Portal') }}</a>
                      @endif
                  @else
                  @endguest



                  <label class="label-btn d-none">
                      <i class="fas fa-bars" id='navbar_btn' onclick="move(true)"></i>
                  </label>
                  @auth
                      <div class="dropdown show">
                          <div class="user-icon align-items-center ml-3" data-toggle="dropdown">
                              <p class='dropdown-text mr-3' style='display: none; text-decoration: underline;'> Opções de usuário</p>
                              <i class="far fa-user-circle mr-3"></i>
                              <i class="fas fa-caret-down"></i>
                          </div>
                          <div class="user-dropdown dropdown-menu">
                              <h4 class="dropdown-header smaller-p">Olá,
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


<style scoped>

/* Media Queries */



@media screen and (max-width:576px){
   .logo-navbar{
    width:84px;
   }
}



</style>

  burgerContainer.addEventListener('click', () => {
    menuSection.classList.toggle('on');
    show = !show
  })
</script>

