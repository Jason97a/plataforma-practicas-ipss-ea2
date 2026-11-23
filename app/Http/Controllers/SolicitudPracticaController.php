<?php

    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Validation\Rule;

    class SolicitudPracticaController extends Controller
    {
        public function index()
        {
            $solicitudes = DB::table('solicitudes')
                ->join(
                    'users',
                    'solicitudes.estudiante_id',
                    '=',
                    'users.id'
                )
                ->select(
                    'solicitudes.*',
                    'users.name as estudiante_nombre'
                )
                ->orderByDesc('solicitudes.created_at')
                ->get();

            return view('solicitudes.index', compact('solicitudes'));
        }

        public function create()
        {
            return view('solicitudes.create');
        }

        public function store(Request $request)
        {
            $datos = $request->validate([
                'anio' => [
                    'required',
                    'integer',
                    Rule::in([(int) date('Y')])
                ],
                'trimestre' => [
                    'required',
                    Rule::in(['T1', 'T2', 'T3'])
                ],
                'modalidad' => [
                    'required',
                    Rule::in([
                        'convencional',
                        'convalidacion',
                        'clinica_tecnologica'
                    ])
                ],
                'empresa_nombre' => 'nullable|string|max:150',
                'empresa_rut' => 'nullable|string|max:20',
                'supervisor_empresa_nombre' => 'nullable|string|max:120',
                'supervisor_empresa_email' => 'nullable|email|max:120',
                'supervisor_empresa_telefono' => 'nullable|string|max:30',
                'funciones_declaradas' => 'nullable|string',
                'modalidad_taller' => [
                    'nullable',
                    'required_if:modalidad,clinica_tecnologica',
                    Rule::in(['individual', 'grupal'])
                ]
            ], [
                'required' => 'El campo :attribute es obligatorio.',
                'required_if' => 'El campo :attribute es obligatorio para esta modalidad.',
                'in' => 'El valor seleccionado en :attribute no es válido.',
                'email' => 'Ingresa un correo electrónico válido.'
            ]);


            $estudianteId = 1;

            if (!DB::table('users')->where('id', $estudianteId)->exists()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'estudiante' => 'No se encontró el estudiante de prueba.'
                    ]);
            }

            $datos['estudiante_id'] = $estudianteId;
            $datos['estado'] = 'enviada';
            $datos['horas_requeridas'] = 360;
            $datos['fecha_envio'] = now();
            $datos['created_at'] = now();
            $datos['updated_at'] = now();

            if ($datos['modalidad'] !== 'clinica_tecnologica') {
                $datos['modalidad_taller'] = null;
            }

            $id = DB::table('solicitudes')->insertGetId($datos);

            $numeroSolicitud = str_pad(
                (string) $id,
                6,
                '0',
                STR_PAD_LEFT
            );

            return redirect()
                ->route('solicitudes.index')
                ->with(
                    'success',
                    '¡Ya realizaste tu inscripción correctamente!'
                )
                ->with('numero_solicitud', $numeroSolicitud);
        }

        public function show($id)
        {
            $solicitud = DB::table('solicitudes')
                ->where('id', $id)
                ->first();

            abort_if(!$solicitud, 404);

            return view('solicitudes.show', compact('solicitud'));
        }
    }
