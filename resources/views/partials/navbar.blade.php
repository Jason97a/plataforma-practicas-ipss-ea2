
<style>
    /* NAVBAR INSTITUCIONAL IPSS */

    .ipss-navbar,
    .ipss-navbar * {
        box-sizing: border-box;
    }

  .ipss-navbar {
    background: #102B40;
    color: white;
    padding: 8px 8%;
    min-height: 70px;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: nowrap;
    gap: 10px;
}

    /* LOGO */

    .ipss-logo-enlace {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .ipss-logo {
        height: 100px;
        width: auto;
        max-width: 300px;
        object-fit: contain;
        display: block;
    }

    /* BOTONES */

    .ipss-nav {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ipss-login {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: #17365d;
        padding: 11px 20px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 15px;
        font-weight: bold;
        white-space: nowrap;
    }

    .ipss-menu-contenedor {
        position: relative;
    }

    .ipss-menu-boton {
        background: transparent;
        color: white;
        border: 1px solid white;
        padding: 11px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 15px;
        font-weight: bold;
        font-family: Arial, sans-serif;
    }

    /* MENÚ DESPLEGABLE */

    .ipss-menu-desplegable {
        display: none;
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        min-width: 230px;
        background: white;
        border-radius: 10px;
        padding: 12px;
        box-shadow: 0 5px 20px #00000025;
        z-index: 1000;
    }

    .ipss-menu-desplegable.abierto {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .ipss-menu-opcion {
        display: block;
        background: #f0f5fc;
        color: #17365d;
        padding: 13px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 15px;
        font-weight: 600;
    }

    .ipss-menu-opcion:hover {
        background: #dbeafe;
    }

    @media (max-width: 850px) {
        .ipss-navbar {
            padding: 15px 5%;
        }

        .ipss-logo {
            height: 65px;
            max-width: 220px;
        }
    }

    @media (max-width: 480px) {
        .ipss-logo {
            height: 50px;
            max-width: 170px;
        }

        .ipss-login,
        .ipss-menu-boton {
            padding: 10px 12px;
            font-size: 13px;
        }
    }
</style>

<header class="ipss-navbar">

    <a href="{{ url('/') }}"
       class="ipss-logo-enlace">

        <img
            src="{{ asset('images/logo.PNG') }}"
            alt="Instituto Profesional San Sebastián"
            class="ipss-logo">
    </a>

    <nav class="ipss-nav"
         aria-label="Navegación principal">

        <a href="{{ route('login') }}"
              class="ipss-login">
          Iniciar sesión
        </a>

        <div class="ipss-menu-contenedor">

            <button type="button"
                    class="ipss-menu-boton"
                    aria-expanded="false">
                ☰ Menú
            </button>

            <div class="ipss-menu-desplegable">

                <a href="{{ url('/') }}"
                   class="ipss-menu-opcion">
                    Inicio
                </a>

                <a href="{{ route('solicitudes.create') }}"
                   class="ipss-menu-opcion">
                    Crear solicitud
                </a>

                <a href="{{ route('solicitudes.index') }}"
                   class="ipss-menu-opcion">
                    Mis solicitudes
                </a>

            </div>
        </div>
    </nav>
</header>

<script>
(function () {
    const navbar = document.currentScript
        .previousElementSibling;

    const boton = navbar.querySelector(
        '.ipss-menu-boton'
    );

    const menu = navbar.querySelector(
        '.ipss-menu-desplegable'
    );

    boton.addEventListener('click', function () {
        const abierto = menu.classList.toggle('abierto');
        boton.setAttribute('aria-expanded', String(abierto));
    });

    document.addEventListener('click', function (evento) {
        if (!navbar.querySelector(
            '.ipss-menu-contenedor'
        ).contains(evento.target)) {
            menu.classList.remove('abierto');
            boton.setAttribute('aria-expanded', 'false');
        }
    });

    document.addEventListener('keydown', function (evento) {
        if (evento.key === 'Escape') {
            menu.classList.remove('abierto');
            boton.setAttribute('aria-expanded', 'false');
        }
    });
})();
</script>
