
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Inicio | Plataforma de Prácticas IPSS</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        /* =========================
           SECCIÓN PRINCIPAL
        ========================= */

        .principal {
            min-height: 420px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 60px 20px;
            background: white;
        }

        .principal h1 {
            font-size: 36px;
            color: #17365d;
            margin-bottom: 20px;
        }

        .principal p {
            font-size: 17px;
            color: #64748b;
            max-width: 750px;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        /* BOTÓN PRINCIPAL */

        .boton-principal {
            display: inline-block;
            background: #17365d;
            color: white;
            padding: 14px 26px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: 0.2s;
        }

        .boton-principal:hover {
            background: #244c7c;
        }

        /*FUNCIONES DE LA PLATAFORMA */

        .contenedor {
            max-width: 1200px;
            margin: 60px auto;
            padding: 0 24px;
        }

        .tarjetas {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .tarjeta {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #0000000b;
            text-align: center;
            transition: 0.2s;
        }

        .tarjeta:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px #00000015;
        }

        .tarjeta h3 {
            color: #17365d;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .tarjeta p {
            color: #64748b;
            line-height: 1.7;
            font-size: 15px;
        }

        .tarjeta a {
            display: inline-block;
            margin-top: 20px;
            background: #17365d;
            color: white;
            padding: 11px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .tarjeta a:hover {
            background: #244c7c;
        }

        /* =========================
           PIE DE PÁGINA
        ========================= */

        footer {
            background: #17365d;
            color: white;
            text-align: center;
            padding: 22px;
            margin-top: 50px;
            font-size: 14px;
        }

        /* =========================
           DISEÑO RESPONSIVE
        ========================= */

        @media (max-width: 850px) {

            .principal h1 {
                font-size: 28px;
            }

            .tarjetas {
                grid-template-columns: 1fr;
            }

            .contenedor {
                margin: 40px auto;
            }

        }

        @media (max-width: 480px) {

            .principal {
                padding: 45px 20px;
            }

            .principal h1 {
                font-size: 25px;
            }

            .principal p {
                font-size: 15px;
            }

            .boton-principal {
                font-size: 14px;
                padding: 12px 20px;
            }

        }

    </style>

</head>

<body>

    <!-- =========================
         NAVBAR INSTITUCIONAL
    ========================= -->

    @include('partials.navbar')


    <!-- =========================
         BIENVENIDA
    ========================= -->

    <section class="principal">

        <h1>
            Bienvenido a la Plataforma de Prácticas IPSS
        </h1>

        <p>
            Esta plataforma permite a los estudiantes gestionar
            sus solicitudes de práctica profesional, convalidación
            y Clínica Tecnológica de manera sencilla y organizada.

            Además, facilita el seguimiento de las solicitudes
            durante las distintas etapas del proceso.
        </p>

        <a href="{{ route('solicitudes.create') }}"
           class="boton-principal">

            Crear nueva solicitud

        </a>

    </section>


    <!-- =========================
         FUNCIONES DE LA PLATAFORMA
    ========================= -->

    <main class="contenedor">

        <div class="tarjetas">

            <!-- CREAR SOLICITUD -->

            <div class="tarjeta">

                <h3>Crear solicitud</h3>

                <p>
                    Inscríbete seleccionando
                    la modalidad que corresponda:
                    Práctica Convencional, Convalidación
                    o Clínica Tecnológica.
                </p>

                <a href="{{ route('solicitudes.create') }}">
                    Crear solicitud
                </a>

            </div>


            <!-- MIS SOLICITUDES -->

            <div class="tarjeta">

                <h3>Mis solicitudes</h3>

                <p>
                    Consulta las solicitudes registradas
                    y revisa la información correspondiente
                    a cada una de ellas.
                </p>

                <a href="{{ route('solicitudes.index') }}">
                    Ver solicitudes
                </a>

            </div>


            <!-- SEGUIMIENTO -->

            <div class="tarjeta">

                <h3>Seguimiento</h3>

                <p>
                    Revisa el estado de tus solicitudes
                    y conoce el avance del proceso
                    de revisión y aprobación.
                </p>

                <a href="{{ route('solicitudes.index') }}">
                    Ver seguimiento
                </a>

            </div>

        </div>

    </main>


    <!-- =========================
         PIE DE PÁGINA
    ========================= -->

    <footer>
        Plataforma de Gestión de Prácticas IPSS
    </footer>

</body>
</html>
