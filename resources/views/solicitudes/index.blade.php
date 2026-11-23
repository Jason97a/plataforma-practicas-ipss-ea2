
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Mis solicitudes | IPSS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        /* CONTENIDO PRINCIPAL */

        .contenedor {
            max-width: 1280px;
            margin: 0 auto;
            padding: 65px 25px 50px;
            min-height: 500px;
        }

        .encabezado-pagina {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 45px;
        }

        .encabezado-pagina h1 {
            color: #19375d;
            font-size: 34px;
            margin: 0 0 15px;
        }

        .encabezado-pagina p {
            color: #444;
            font-size: 16px;
            margin: 0;
        }

        /* BOTÓN NUEVA SOLICITUD */

        .boton-nueva {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #19375d;
            color: white;
            padding: 15px 25px;
            border-radius: 7px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            transition: background 0.2s;
        }

        .boton-nueva:hover {
            background: #102b40;
        }

        /* MENSAJE DE INSCRIPCIÓN EXITOSA */

        .mensaje-exito {
            background: #e8f7ee;
            border: 1px solid #9bd8b2;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            color: #166534;
            box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        }

        .mensaje-exito h3 {
            margin: 0 0 12px;
            font-size: 21px;
        }

        .mensaje-exito p {
            margin: 10px 0;
            line-height: 1.6;
        }

        .numero-confirmacion {
            background: white;
            border: 1px solid #bbebcb;
            border-radius: 8px;
            padding: 15px 20px;
            margin: 18px 0;
        }

        .numero-confirmacion small {
            display: block;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .numero-confirmacion strong {
            font-size: 26px;
            color: #19375d;
            letter-spacing: 1px;
        }

        /* TARJETA DE SOLICITUDES */

        .tarjeta {
            background: white;
            padding: 28px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.07);
        }

        .tarjeta h2 {
            font-size: 26px;
            margin: 15px 0 20px;
            color: #333;
        }

        /* TABLA */

        .tabla-contenedor {
            width: 100%;
            overflow-x: auto;
        }

        .tabla-solicitudes {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
            text-align: left;
        }

        .tabla-solicitudes thead {
            background: #eaf0f6;
        }

        .tabla-solicitudes th {
            padding: 17px 15px;
            color: #19375d;
            font-weight: bold;
            white-space: nowrap;
        }

        .tabla-solicitudes td {
            padding: 17px 15px;
            border-bottom: 1px solid #e5e7eb;
        }

        .tabla-solicitudes tbody tr:hover {
            background: #f8fafc;
        }

        /* ESTADOS */

        .estado {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            background: #e5e7eb;
            color: #374151;
        }

        .estado-enviada,
        .estado-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .estado-aprobada {
            background: #dcfce7;
            color: #166534;
        }

        .estado-rechazada {
            background: #fee2e2;
            color: #991b1b;
        }

        .sin-solicitudes {
            text-align: center;
            padding: 40px 15px !important;
            color: #666;
        }

        /* PIE DE PÁGINA */

        .pie-pagina {
            background: #102b40;
            color: white;
            text-align: center;
            padding: 22px 15px;
            font-size: 16px;
        }

        /* RESPONSIVE */

        @media (max-width: 768px) {
            .contenedor {
                padding: 35px 15px;
            }

            .encabezado-pagina h1 {
                font-size: 27px;
            }

            .boton-nueva {
                width: 100%;
            }

            .tarjeta {
                padding: 20px 15px;
            }

            .tarjeta h2 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR INSTITUCIONAL -->
    @include('partials.navbar')

    <!-- CONTENIDO PRINCIPAL -->
    <main class="contenedor">

        <!-- CONFIRMACIÓN DE INSCRIPCIÓN -->
        @if (session('success'))
            <div class="mensaje-exito" role="status">

                <h3>
                    ✓ {{ session('success') }}
                </h3>

                <p>
                    Tu solicitud de práctica ha sido registrada
                    correctamente en la Plataforma de Gestión
                    de Prácticas IPSS.
                </p>

                @if (session('numero_solicitud'))
                    <div class="numero-confirmacion">
                        <small>NÚMERO DE SOLICITUD</small>

                        <strong>
                            {{ session('numero_solicitud') }}
                        </strong>
                    </div>
                @endif

                <p>
                    <strong>Estado:</strong> Enviada
                </p>

                <p>
                    Puedes consultar el estado de tu inscripción
                    en el listado de solicitudes.
                </p>

            </div>
        @endif

        <!-- ENCABEZADO -->
        <div class="encabezado-pagina">

            <div>
                <h1>Mis solicitudes de práctica</h1>

                <p>
                    Consulta las solicitudes registradas
                    y revisa su estado.
                </p>
            </div>

            <a href="{{ route('solicitudes.create') }}"
               class="boton-nueva">
                + Nueva solicitud
            </a>

        </div>

        <!-- LISTADO DE SOLICITUDES -->
        <section class="tarjeta">

            <h2>Listado de solicitudes</h2>

            <div class="tabla-contenedor">

                <table class="tabla-solicitudes">

                    <thead>
                        <tr>
                            <th>N.º solicitud</th>
                            <th>Estudiante</th>
                            <th>Período académico</th>
                            <th>Modalidad</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($solicitudes as $solicitud)

                            @php
                                $estado = strtolower(
                                    (string) ($solicitud->estado ?? 'pendiente')
                                );

                                $claseEstado = match ($estado) {
                                    'enviada', 'pendiente' => 'estado-enviada',
                                    'aprobada', 'aprobado' => 'estado-aprobada',
                                    'rechazada', 'rechazado' => 'estado-rechazada',
                                    default => '',
                                };

                                $modalidad = match (
                                    $solicitud->modalidad ?? ''
                                ) {
                                    'convencional' => 'Práctica convencional',
                                    'convalidacion' => 'Convalidación',
                                    'clinica_tecnologica' => 'Clínica tecnológica',
                                    default => $solicitud->modalidad ?? '—',
                                };

                                $numeroSolicitud = str_pad(
                                    (string) $solicitud->id,
                                    6,
                                    '0',
                                    STR_PAD_LEFT
                                );
                            @endphp

                            <tr>

                                <td>
                                    <strong>
                                        {{ $numeroSolicitud }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $solicitud->estudiante_nombre ?? '—' }}
                                </td>

                                <td>
                                    {{ $solicitud->anio ?? '—' }}
                                    -
                                    {{ $solicitud->trimestre ?? '—' }}
                                </td>

                                <td>
                                    {{ $modalidad }}
                                </td>

                                <td>
                                    <span class="estado {{ $claseEstado }}">
                                        {{ ucfirst($estado) }}
                                    </span>
                                </td>

                                <td>
                                    @if(!empty($solicitud->created_at))
                                        {{ \Carbon\Carbon::parse($solicitud->created_at)->format('d/m/Y') }}
                                    @else
                                        —
                                    @endif
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6"
                                    class="sin-solicitudes">
                                    No hay solicitudes registradas.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    </main>

    <!-- PIE DE PÁGINA -->
    <footer class="pie-pagina">
        Plataforma de Gestión de Prácticas - IPSS
    </footer>

</body>
</html>
