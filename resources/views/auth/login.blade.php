
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Iniciar sesión | IPSS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(
                120deg,
                #f5f6f7,
                #dce1e5
            );
            color: #212529;
        }

        .login-contenedor {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 35px 15px;
        }

        .login-tarjeta {
            width: 100%;
            max-width: 535px;
            background: white;
            padding: 48px 52px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.10);
        }

        .login-logo {
            background: #102B40;
            border-radius: 10px;
            padding: 28px;
            min-height: 210px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-bottom: 5px solid #c5a35d;
        }

        .login-logo img {
            width: 100%;
            max-width: 390px;
            height: auto;
            object-fit: contain;
        }

        .separador {
            border: none;
            border-top: 1px solid #dce1e5;
            margin: 27px 0;
        }

        h2 {
            font-size: 22px;
            margin: 0 0 10px;
        }

        .boton-ms {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid #d5dce3;
            border-radius: 8px;
            background: white;
            color: #333;
            font-size: 15px;
            text-decoration: none;
            cursor: pointer;
        }

        .boton-ms:hover {
            background: #f1f5f9;
        }

        .boton-ms svg {
            width: 22px;
            height: 22px;
        }

        .texto {
            font-size: 15px;
            line-height: 1.65;
        }

        .texto p {
            margin-bottom: 18px;
        }

        .mensaje {
            padding: 12px;
            margin-bottom: 15px;
            background: #e3efff;
            border-radius: 8px;
            color: #17365d;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            .login-tarjeta {
                padding: 25px;
            }

            .login-logo {
                min-height: 150px;
                padding: 18px;
            }

            h2 {
                font-size: 19px;
            }
        }
    </style>
</head>

<body>

    <main class="login-contenedor">

        <div class="login-tarjeta">

            <!-- LOGO INSTITUCIONAL -->
            <div class="login-logo">
                <img
                    src="{{ asset('images/logo.PNG') }}"
                    alt="Instituto Profesional San Sebastián">
            </div>

            <hr class="separador">

            <h2>Para ingresar usa tu cuenta:</h2>



            <hr class="separador">

            <h2>Acceso a la plataforma</h2>

            <div class="texto">

                <p>
                    <strong>Bienvenido/(a) a la Plataforma
                    de Gestión de Prácticas del Instituto
                    Profesional San Sebastián.</strong>
                </p>

            </div>

        </div>

    </main>

    <script>
        document.getElementById('boton-acceso-ms')
            .addEventListener('click', function (event) {
                event.preventDefault();

                alert(
                    'El acceso Microsoft todavía no está configurado.'
                );
            });
    </script>

</body>
</html>
