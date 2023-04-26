
<!-- {{-- <footer class="mt-auto bg-light text-center text-lg-start"> --}}
<footer class="mt-auto bg-dark text-center text-lg-start"> -->
    <!-- Copyright -->

    <!--<div class="text-center text-light p-3" style="background-color: rgba(0, 0, 0, 0.2)">
        {{-- © 2021 Copyright: --}}
        {{-- <a class="text-dark" href="https://mdbootstrap.com/">MDBootstrap.com</a> --}}
        <a class="text-light" href="https://www.imd.ufrn.br/portal/">IMD - Instituto Metrópole Digital</a>
        © 2021

        {{-- MODELO ALTERNATIVO --}}
        {{-- <a class="text-light" href="https://www.imd.ufrn.br/portal/">Instituto Metrópole Digital</a>
        - Natal 2021 - Todos os direitos reservados --}}
    </div> -->
    <!-- Copyright -->
<!--</footer> -->

<footer class="footer">
    <div class="h-100 d-flex flex-column justify-content-around container">
        <div id="info-div" class=" row justify-content-between align-items-center">
            <div id="section1">
                <div class="logo-and-title d-flex align-items-center">
                    <img fluid id="imd-footer" src="{{ asset('img/imd-footer-2.png') }}"/>
                    <p id="pdd-title" class="ml-3 footer-title">Portal das Disciplinas - IMD/UFRN</p>
                </div>
                <!-- <p id="pdd-sub" class="mt-3 footer-sub">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Delectus culpa est recusandae.</p> -->
            </div>
            <div>
                <img id="ufrn-logo" class='blackImg' fluid src="{{ asset('img/ufrnlogo-alt.png') }}"/>
            </div>
        </div>
        <div class="row">
            <hr>
            <div id="eqp-div" class="d-flex justify-content-between w-100 mt-2 pb-3 info-imd">
                <div class="mt-1">&copy; Instituto Metrópole Digital. Natal, <?php echo date('Y') ?>. Todos os direitos reservados.</div>
                <div id="eqp-link">Desenvolvido por: <a href="{{route('information')}}" class="eqp-link">Equipe do Portal das Disciplinas</a></div>
            </div>
        </div>
    </div>
</footer>

<style scoped>

#ufrn-logo{
    width: 150px;
    opacity: .6;
}

#imd-footer{
    width: 44px;
}

.footer{
    height: auto;
    background-color: white;
    width: 100vw;
    bottom: 0;
}

p{
    margin: 0;
}

.footer-title{
    color: #111827;
    font-style: normal;
    font-weight: bold;
    font-size: 23px;
    line-height: 24px;
}

.footer-sub{
    color: #6B7280;
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 24px;
}

#section1{
    max-width: 440px;
}

hr{
    margin: 0;
    padding: 0;
    width: 100%;
    position: relative;
    z-index: 1000;
}

.info-imd, .eqp-link{
    color: #6B7280;
}

.eqp-link{
    text-decoration: underline;
    transition: .4s;
}

.eqp-link:hover{
    color: #3490dc;
}

.info-imd{
    font-family: Nunito Sans;
    font-style: normal;
    font-weight: normal;
    font-size: 14px;
    line-height: 16px;
}

#eqp-link{
    text-align: right;
}

/* Media Queries */

@media screen and (max-width:770px){
    #info-div{
        flex-direction: column;
        text-align: center;
        margin-top: 2rem;
    }

    #ufrn-logo{
        display: none;
    }
    .footer{
        background-image: url('../assets/img/ufrnlogo-alt-bg.png');
        background-repeat: no-repeat;
        background-position: center;
        background-size: 140px;
    }

    #eqp-div, #pdd-sub{
        color: var(--grey-900);
    }
}

@media screen and (max-width:576px){
    #eqp-div{
        flex-direction: column;
        text-align: center;
    }

    #eqp-link{
        text-align: center;
    }

    .footer{
        height: auto;
        padding: 15px;
    }

    .eqp-link{
        color: var(--azul-primario) !important;
    }

    .eqp-link:hover{
        color: var(--azul-waves-alt) !important;
    }

    #info-div{
        margin-top: 1rem !important;
    }
}

@media screen and (max-width:768px){
    .logo-and-title{
        flex-direction: column;
    }
    .logo-and-title > *{
        margin: 1.6rem 0;
    }
    #imd-footer{
        display: block;
    }
    #pdd-title{
        width: 100%;
        text-align: center !important;
    }
}

</style>
