<header>
    <nav class="navbar  navbar-light bg-light">

      {{-- Desativado por enquanto --}}
      <label class="label-btn">
        <i class="fas fa-bars" id='navbar_btn' onclick="move(true)"></i>
      </label>

        <a class="navbar-brand mr-0" href="{{route('index')}}">
            <img src="{{asset('img/imdLogo.png')}}"  class='logo-navbar'alt="Logo do IMD">
        </a>

        <div class="" id="navbarNav">
          {{-- <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="#">Sobre</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">FAQ</a>
            </li>
          </ul> --}}

          <ul class="navbar-nav ml-auto">

            @guest
                @if (Route::has('login'))
                    <li class="nav-item">
                        <a class="btn btn-outline-primary" href="{{ route('login') }}">{{ __('Entrar') }}</a>
                    </li>
                @endif
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endguest
          </ul>
        </div>




    </nav>
</header>
