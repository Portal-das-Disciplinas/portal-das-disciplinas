<header class="sticky-top shadow">
    <div class="menu-container container d-flex mt-0 justify-content-between align-items-center">
        <a class="navbar-brand" href="{{ route('index') }}">
            <img src="{{ asset('storage/img/logo.png') }}" class='logo-navbar' alt="Logo do {{ $theme['PROJETO_SIGLA_SETOR']}}">
        </a>
        <div class="menu-section">
            <div class="container-menu-burger">
                <div class="menu-burger"></div>
            </div>
            <nav>
                <a href="{{ route('index') }}">Inicio</a>
                <a href="{{ route('information')}}">Sobre & Colabore</a>
                @if(Auth::user() && Auth::user()->is_admin)
                @if(isset($opinionLinkForm))
                <div style="position:relative">
                    <a style="cursor:pointer;" data-toggle="dropdown">
                        @if($opinionLinkForm->active)
                        <small class="text-success">
                            Link Ativo para formulário
                        </small>
                        @else
                        <small class="text-danger">
                            Link inativo para formulário
                        </small>
                        @endif
                    </a>
                    <div id="dropdown-opinion" class="dropdown-menu" style="position:absolute; margin:-20px 25px">
                        <a class="dropdown-item" href="{{$opinionLinkForm->url}}" target="_blank">Acessar link</a>
                        <div class="dropdown-divider"></div>
                        <a style="cursor:pointer" onclick="$('#modalUrlForm').modal('show')" class="dropdown-item">Editar Link</a>
                        <form id="formToggleLink" method='post' action="{{route('links.active.toggle')}}" class="p-0 m-0">
                            @csrf
                            @method('PUT')
                            <input name="idOpinion" hidden value="{{$opinionLinkForm->id}}">
                            <a style="cursor:pointer" class="dropdown-item" onclick="$('#formToggleLink').submit()">
                                {{$opinionLinkForm->active ? 'Inativar' : 'Ativar'}}
                            </a>
                        </form>
                        <form id="formDeleteLink" method='post' action="{{route('links.destroy',$opinionLinkForm->id)}}">
                            @csrf
                            @method('DELETE')
                            <a style="cursor:pointer" onclick="$('#formDeleteLink').submit()" class="dropdown-item">Remover Link</a>
                        </form>
                    </div>
                </div>
                @elseif(isset($showOpinionForm))
                <a class="text-primary" style="cursor:pointer" onclick="$('#modalUrlForm').modal('show')"><small>[sem link para formulário]</small></a>
                @endif
                @elseif(isset($opinionLinkForm) && $opinionLinkForm->active)
                <a href="{{$opinionLinkForm->url}}" target="_blank">Dê sua opinião</a>
                @endif

                @guest
                @if(Route::has('login'))
                <a href="{{ route('login') }}" class='access-btn'>{{ __('Acesso Professores') }}</a>
                @endif
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
                                <a href="{{ route('disciplinas.create') }}">
                                    <div class="dropdown-item py-3"> <i class="fas fa-book mr-2"></i></i>
                                        Cadastrar
                                        disciplina </div>
                                </a>
                                @if(auth()->user()->is_admin)
                                <a href="{{ route('professores.index') }}">
                                    <div class="dropdown-item py-3"> <i class="fas fa-users-cog mr-2"></i>Painel
                                        de Administração</div>
                                </a>
                                <a href="{{ route('classificacoes.index') }}">
                                    <div class="dropdown-item py-3"> <i class="fas fa-star mr-2 "></i>Painel
                                        de
                                        Classificações</div>
                                </a>
                                <a href="{{ route('methodology.painel') }}">
                                    <div class="dropdown-item py-3"> <i class="fas fa-lightbulb mr-2 "></i>Painel
                                        de
                                        Metodoloigias</div>
                                </a>
                                <a href="{{ route('configuracoes.index') }}">
                                    <div class="dropdown-item py-3"> <i class="fas fa-cogs mr-2"></i>Configurações</div>
                                </a>
                                <a href="{{ route('scheduling.index') }}">
                                    <div class="dropdown-item py-3">
                                        <i class="fas fa-chart-bar mr-2"></i>
                                        Dados de desempenho das disciplinas
                                    </div>
                                </a>

                                <a href="{{ route('portal_access_info.index')}}">
                                    <div class="dropdown-item py-3">
                                        <i class="fas fa-chart-line"></i>
                                        Dados de Acessos ao Portal
                                    </div>
                                </a>
                                @endif

                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                  document.getElementById('logout-form').submit();" class=''>
                                    <div class="dropdown-item py-3"><i class="fas fa-sign-out-alt mr-2"></i></i>{{ __('Logout') }}
                                    </div>


                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                </form>

                    </div>
                </div>
                @endauth
            </nav>
        </div>
    </div>

</header>

<div id="modalUrlForm" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Link para o formulário</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUrlForm" class="form" method='post' action="{{route('links.supdate.opinion_form_link')}}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="urlForm">URL do link para o formulário</label>
                        <input id="urlForm" class="form-control" name="urlForm" type="url" placeholder="https://..." required value="{{isset($opinionLinkForm) ? $opinionLinkForm->url : ''}}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="$('#formUrlForm').submit()" type="button" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>



<script>
    let show = false
    const menuSection = document.querySelector('.menu-section');
    const burgerContainer = document.querySelector('.container-menu-burger')

    burgerContainer.addEventListener('click', () => {
        menuSection.classList.toggle('on');
        show = !show
    })
</script>