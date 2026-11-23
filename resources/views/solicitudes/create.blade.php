
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva solicitud | IPSS</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f5f9;
            color: #102B40;
        }

        .contenedor {
            max-width: 1300px;
            margin: 35px auto;
            padding: 0 24px;
        }

        .contenedor h1 {
            color: #17365d;
            font-size: 30px;
        }

        .contenedor h3 {
            color: #17365d;
            font-size: 17px;
        }

        .subtitulo {
            color: #64748b;
        }

        .tarjeta {
            background: white;
            border-radius: 14px;
            padding: 32px;
            margin-top: 30px;
            display: grid;
            grid-template-columns: 40% 1fr;
            gap: 36px;
            box-shadow: 0 4px 15px #0000000b;
            align-items: start;
        }

        .titulo-panel {
            background: #e3efff;
            color: #17365d;
            padding: 20px;
            font-size: 19px;
            font-weight: bold;
        }

        .panel-informativo ul {
            list-style: none;
            counter-reset: paso;
            padding: 0;
            margin: 0;
        }

        .panel-informativo li {
            counter-increment: paso;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding: 20px 12px;
            border-bottom: 1px solid #e2e8f0;
            line-height: 1.5;
        }

        .panel-informativo li::before {
            content: counter(paso);
            background: #17365d;
            color: white;
            font-weight: bold;
            width: 30px;
            height: 30px;
            min-width: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fila {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .campo {
            margin-bottom: 22px;
        }

        .campo label {
            display: block;
            font-weight: bold;
            color: #17365d;
            margin-bottom: 10px;
        }

        .campo input,
        .campo select,
        .campo textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font: inherit;
            background: white;
        }

        .campo input:focus,
        .campo select:focus,
        .campo textarea:focus {
            outline: 2px solid #93b4e0;
        }

        .seccion {
            margin-top: 28px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .integrante {
            padding: 18px;
            border: 1px solid #d5dfeb;
            border-radius: 10px;
            margin-bottom: 16px;
        }

        .opciones {
            display: flex;
            gap: 24px;
            margin: 15px 0 25px;
        }

        .opciones label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
        }

        .opciones input {
            width: auto;
        }

        .contenedor button {
            padding: 13px 22px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        .agregar {
            background: white;
            border: 1px solid #17365d;
            color: #17365d;
        }

        .agregar:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .guardar {
            background: #17365d;
            color: white;
            border: none;
            margin-top: 20px;
        }

        .guardar:hover {
            background: #244c7c;
        }

        .quitar {
            background: white;
            border: 1px solid #dc2626;
            color: #dc2626;
        }

        .oculto {
            display: none;
        }

        .errores {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        @media (max-width: 850px) {
            .tarjeta {
                grid-template-columns: 1fr;
            }

            .fila {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR COMPARTIDO -->
    @include('partials.navbar')

    <main class="contenedor">

        <h1>Nueva solicitud</h1>

        <p class="subtitulo">
            Completa el formulario para tu inscripción.
        </p>

        @if ($errors->any())
            <div class="errores">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tarjeta">

            <!-- PANEL INFORMATIVO -->
            <aside class="panel-informativo">

                <div class="titulo-panel">
                    ⓘ Información importante para tu solicitud
                </div>

                <ul>
                    <li>Completa tus datos personales.</li>
                    <li>Selecciona el año y trimestre correspondiente.</li>
                    <li>Indica la modalidad de práctica que realizarás.</li>
                    <li>Completa los campos correspondientes a tu modalidad.</li>
                    <li>Revisa la información antes de guardar la solicitud.</li>
                </ul>

            </aside>

            <!-- FORMULARIO PRINCIPAL -->
            <form action="{{ route('solicitudes.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  id="formulario">

                @csrf

                <!-- DATOS DEL ESTUDIANTE -->
                <h3>Datos del estudiante</h3>

                <div class="campo">
                    <label for="responsable_nombre">
                        Nombre completo del estudiante
                    </label>

                    <input type="text"
                           id="responsable_nombre"
                           name="responsable_nombre"
                           maxlength="120"
                           value="{{ old('responsable_nombre') }}"
                           required>
                </div>

                <div class="campo">
                    <label for="responsable_rut">
                        RUT del estudiante
                    </label>

                    <input type="text"
                           id="responsable_rut"
                           name="responsable_rut"
                           maxlength="10"
                           placeholder="12345678-9"
                           value="{{ old('responsable_rut') }}"
                           required>
                </div>

                <div class="campo">
                    <label for="responsable_carrera">
                        Carrera
                    </label>

                    <input type="text"
                           id="responsable_carrera"
                           name="responsable_carrera"
                           maxlength="150"
                           value="{{ old('responsable_carrera') }}"
                           required>
                </div>

                <!-- DATOS DE LA SOLICITUD -->
                <div class="seccion">

                    <h3>Datos de la solicitud</h3>

                    <div class="fila">

                        <div class="campo">
                        <label for="anio">
                            Año académico
                             </label>

                                <input type="number"
                                id="anio"
                                name="anio"
                                value="{{ date('Y') }}"
                               min="{{ date('Y') }}"
                               max="{{ date('Y') }}"
                              readonly
                              required>
                    </div>
                        <div class="campo">
                            <label for="trimestre">
                                Trimestre
                            </label>

                            <select id="trimestre"
                                    name="trimestre"
                                    required>

                                @foreach (['T1', 'T2', 'T3'] as $t)
                                    <option value="{{ $t }}"
                                        @selected(old('trimestre') === $t)>
                                        {{ $t }}
                                    </option>
                                @endforeach

                            </select>
                        </div>

                    </div>

                    <div class="campo">
                        <label for="modalidad">
                            Modalidad de práctica
                        </label>

                        <select name="modalidad"
                                id="modalidad"
                                required>

                            <option value="">
                                Seleccionar modalidad
                            </option>

                            <option value="convencional"
                                @selected(old('modalidad') === 'convencional')>
                                Práctica convencional
                            </option>

                            <option value="convalidacion"
                                @selected(old('modalidad') === 'convalidacion')>
                                Convalidación
                            </option>

                            <option value="clinica_tecnologica"
                                @selected(old('modalidad') === 'clinica_tecnologica')>
                                Clínica tecnológica
                            </option>

                        </select>
                    </div>

                </div>

                <!-- PRÁCTICA CONVENCIONAL Y CONVALIDACIÓN -->
                <div id="formulario-empresa" class="oculto">

                    <div class="seccion">

                        <h3>Datos de la empresa</h3>

                        <div class="campo">
                            <label for="empresa_nombre">
                                Nombre de la empresa
                            </label>

                            <input type="text"
                                   id="empresa_nombre"
                                   name="empresa_nombre"
                                   value="{{ old('empresa_nombre') }}">
                        </div>

                        <div class="campo">
                            <label for="empresa_rut">
                                RUT de la empresa
                            </label>

                            <input type="text"
                                   id="empresa_rut"
                                   name="empresa_rut"
                                   maxlength="10"
                                   placeholder="12345678-9"
                                   value="{{ old('empresa_rut') }}">
                        </div>

                    </div>

                    <div class="seccion">

                        <h3>Supervisor de la empresa</h3>

                        <div class="campo">
                            <label for="supervisor_empresa_nombre">
                                Nombre del supervisor
                            </label>

                            <input type="text"
                                   id="supervisor_empresa_nombre"
                                   name="supervisor_empresa_nombre"
                                   value="{{ old('supervisor_empresa_nombre') }}">
                        </div>

                        <div class="fila">

                            <div class="campo">
                                <label for="supervisor_empresa_email">
                                    Correo del supervisor
                                </label>

                                <input type="email"
                                       id="supervisor_empresa_email"
                                       name="supervisor_empresa_email"
                                       value="{{ old('supervisor_empresa_email') }}">
                            </div>

                            <div class="campo">
                                <label for="supervisor_empresa_telefono">
                                    Teléfono del supervisor
                                </label>

                                <input type="tel"
                                       id="supervisor_empresa_telefono"
                                       name="supervisor_empresa_telefono"
                                       value="{{ old('supervisor_empresa_telefono') }}">
                            </div>

                        </div>

                        <div class="campo">
                            <label for="funciones_declaradas"
                                   id="etiqueta-funciones">
                                Funciones que realizará el estudiante
                            </label>

                            <textarea id="funciones_declaradas"
                                      name="funciones_declaradas"
                                      rows="4">{{ old('funciones_declaradas') }}</textarea>
                        </div>

                    </div>
                </div>

                <!-- DOCUMENTACIÓN PARA CONVALIDACIÓN -->
                <div id="documentos-convalidacion"
                     class="seccion oculto">

                    <h3>Documentación de respaldo laboral</h3>

                    <p class="subtitulo">
                        Para solicitar la convalidación de tu práctica
                        profesional, debes adjuntar tus cotizaciones
                        previsionales correspondientes a la empresa
                        donde trabajas o trabajaste.
                    </p>

                    <div class="campo">
                        <label for="cotizaciones_previsionales">
                            Cotizaciones previsionales (obligatorio)
                        </label>

                        <input type="file"
                               id="cotizaciones_previsionales"
                               name="cotizaciones_previsionales"
                               accept=".pdf,application/pdf"
                               disabled>

                        <small>
                            Formato permitido: PDF. Tamaño máximo: 10 MB.
                        </small>
                    </div>

                </div>

                <!-- CLÍNICA TECNOLÓGICA -->
                <div id="formulario-clinica" class="oculto">

                    <div class="seccion">

                        <h3>Inscripción Clínica Tecnológica</h3>

                        <p>
                            Selecciona si realizarás el proyecto
                            de forma individual o grupal.
                        </p>

                        <div class="opciones">

                            <label>
                                <input type="radio"
                                       name="modalidad_taller"
                                       value="individual"
                                       @checked(old('modalidad_taller', 'individual') === 'individual')>
                                Individual
                            </label>

                            <label>
                                <input type="radio"
                                       name="modalidad_taller"
                                       value="grupal"
                                       @checked(old('modalidad_taller') === 'grupal')>
                                Grupal
                            </label>

                        </div>

                        <!-- INTEGRANTES ADICIONALES -->
                        <div id="grupo-integrantes" class="oculto">

                            <h3>Integrantes adicionales</h3>

                            <p>
                                Puedes incluir uno o dos compañeros
                                para participar en el proyecto.
                                El grupo puede tener un máximo
                                de tres estudiantes.
                            </p>

                            <div id="integrantes"></div>

                            <button type="button"
                                    class="agregar"
                                    id="agregar-persona">
                                + Incluir persona
                            </button>

                        </div>

                    </div>
                </div>

                <!-- GUARDAR SOLICITUD -->
                <button type="submit" class="guardar">
                    Registrar solicitud
                </button>

            </form>

        </div>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        const modalidad = document.getElementById('modalidad');
        const empresa = document.getElementById('formulario-empresa');
        const clinica = document.getElementById('formulario-clinica');

        const documentosConvalidacion =
            document.getElementById('documentos-convalidacion');

        const archivoCotizaciones =
            document.getElementById('cotizaciones_previsionales');

        const grupo = document.getElementById('grupo-integrantes');
        const integrantes = document.getElementById('integrantes');
        const agregar = document.getElementById('agregar-persona');

        const radios = document.querySelectorAll(
            'input[name="modalidad_taller"]'
        );

        function activar(seccion, activo) {

            seccion.classList.toggle('oculto', !activo);

            seccion.querySelectorAll('input, select, textarea')
                .forEach(campo => {
                    campo.disabled = !activo;
                });
        }

        // MOSTRAR CAMPOS SEGÚN MODALIDAD
        function actualizarModalidad() {

            const esEmpresa =
                ['convencional', 'convalidacion']
                    .includes(modalidad.value);

            const esClinica =
                modalidad.value === 'clinica_tecnologica';

            const esConvalidacion =
                modalidad.value === 'convalidacion';

            activar(empresa, esEmpresa);
            activar(clinica, esClinica);
            activar(documentosConvalidacion, esConvalidacion);

            // El PDF solo es obligatorio para Convalidación
            archivoCotizaciones.required = esConvalidacion;

            document.getElementById('etiqueta-funciones').textContent =
                esConvalidacion
                    ? 'Funciones y experiencia laboral'
                    : 'Funciones que realizará el estudiante';

            actualizarGrupo();
        }

        // INSCRIPCIÓN INDIVIDUAL O GRUPAL
        function actualizarGrupo() {

            const grupal =
                !clinica.classList.contains('oculto') &&
                document.querySelector(
                    'input[name="modalidad_taller"]:checked'
                )?.value === 'grupal';

            grupo.classList.toggle('oculto', !grupal);

            if (grupal && integrantes.children.length === 0) {
                agregarPersona();
            }

            grupo.querySelectorAll('input')
                .forEach(campo => {
                    campo.disabled = !grupal;
                });

            agregar.disabled =
                integrantes.children.length >= 2;
        }

        // AGREGAR INTEGRANTES
        function agregarPersona() {

            if (integrantes.children.length >= 2) {
                return;
            }

            const numero =
                integrantes.children.length + 2;

            const div = document.createElement('div');

            div.className = 'integrante';

            div.innerHTML = `
                <h3>Integrante ${numero}</h3>

                <div class="campo">
                    <label>Nombre completo</label>

                    <input type="text"
                           class="nombre-integrante"
                           required
                           maxlength="120">
                </div>

                <div class="campo">
                    <label>RUT</label>

                    <input type="text"
                           class="rut-integrante"
                           placeholder="12345678-9"
                           required
                           maxlength="10">
                </div>

                <button type="button" class="quitar">
                    Quitar integrante
                </button>
            `;

            div.querySelector('.quitar')
                .addEventListener('click', () => {
                    div.remove();
                    renumerar();
                });

            integrantes.appendChild(div);

            renumerar();
        }

        // RENUMERAR INTEGRANTES
        function renumerar() {

            [...integrantes.children].forEach((div, i) => {

                div.querySelector('h3').textContent =
                    `Integrante ${i + 2}`;

                div.querySelector('.nombre-integrante').name =
                    `integrantes[${i}][nombre]`;

                div.querySelector('.rut-integrante').name =
                    `integrantes[${i}][rut]`;
            });

            agregar.disabled =
                integrantes.children.length >= 2;
        }

        // EVENTOS
        agregar.addEventListener('click', agregarPersona);

        modalidad.addEventListener(
            'change',
            actualizarModalidad
        );

        radios.forEach(radio => {
            radio.addEventListener(
                'change',
                actualizarGrupo
            );
        });

        actualizarModalidad();
    });
    </script>

</body>
</html>
