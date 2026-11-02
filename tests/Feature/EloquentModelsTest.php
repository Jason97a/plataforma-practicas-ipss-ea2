<?php

namespace Tests\Feature;

use App\Models\DocumentoSolicitud;
use App\Models\GrupoTaller;
use App\Models\HistorialEstado;
use App\Models\Solicitud;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class EloquentModelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_roles_helpers_and_solicitud_modalidad_taller_rules(): void
    {
        $estudiante = User::factory()->create([
            'rut' => '20.111.222-3',
            'rol' => User::ROL_ESTUDIANTE,
            'carrera' => 'Ingeniería en Informática',
            'nivel_formativo' => User::NIVEL_INGENIERIA,
        ]);

        $coordinador = User::factory()->create([
            'rol' => User::ROL_COORDINADOR,
        ]);

        $this->assertTrue($estudiante->esEstudiante());
        $this->assertTrue($coordinador->esCoordinador());
        $this->assertTrue($coordinador->tieneRol(User::ROL_COORDINADOR, User::ROL_ADMIN));

        // Convencional -> fuerza taller individual
        $convencional = Solicitud::create([
            'estudiante_id' => $estudiante->id,
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T3,
            'modalidad' => Solicitud::MODALIDAD_CONVENCIONAL,
            'modalidad_taller' => Solicitud::TALLER_GRUPAL, // Intenta violar la regla
        ]);
        $this->assertSame(Solicitud::TALLER_INDIVIDUAL, $convencional->modalidad_taller);
        $this->assertFalse($convencional->permiteGrupoTaller());

        // Convalidación -> fuerza taller individual
        $convalidacion = Solicitud::create([
            'estudiante_id' => $estudiante->id,
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T3,
            'modalidad' => Solicitud::MODALIDAD_CONVALIDACION,
            'modalidad_taller' => Solicitud::TALLER_GRUPAL,
        ]);
        $this->assertSame(Solicitud::TALLER_INDIVIDUAL, $convalidacion->modalidad_taller);

        // Clínica Tecnológica -> fuerza taller grupal
        $clinica = Solicitud::create([
            'estudiante_id' => $estudiante->id,
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T3,
            'modalidad' => Solicitud::MODALIDAD_CLINICA,
            'modalidad_taller' => Solicitud::TALLER_INDIVIDUAL,
        ]);
        $this->assertSame(Solicitud::TALLER_GRUPAL, $clinica->modalidad_taller);
        $this->assertTrue($clinica->permiteGrupoTaller());
    }

    public function test_historial_estado_exige_motivo_en_observacion_o_rechazo(): void
    {
        $estudiante = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
        $coordinador = User::factory()->create(['rol' => User::ROL_COORDINADOR]);

        $solicitud = Solicitud::create([
            'estudiante_id' => $estudiante->id,
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T1,
            'modalidad' => Solicitud::MODALIDAD_CONVENCIONAL,
        ]);

        $historialValido = HistorialEstado::create([
            'solicitud_id' => $solicitud->id,
            'usuario_id' => $coordinador->id,
            'estado_anterior' => Solicitud::ESTADO_ENVIADA,
            'estado_nuevo' => Solicitud::ESTADO_CON_OBSERVACIONES,
            'motivo_u_observacion' => 'Falta firma del supervisor en la ficha.',
        ]);

        $this->assertCount(1, $solicitud->historialEstados);
        $this->assertSame($coordinador->id, $historialValido->usuario->id);

        $this->expectException(InvalidArgumentException::class);

        HistorialEstado::create([
            'solicitud_id' => $solicitud->id,
            'usuario_id' => $coordinador->id,
            'estado_anterior' => Solicitud::ESTADO_EN_REVISION,
            'estado_nuevo' => Solicitud::ESTADO_RECHAZADA,
            'motivo_u_observacion' => '   ',
        ]);
    }

    public function test_documento_solicitud_rechaza_rutas_publicas(): void
    {
        $estudiante = User::factory()->create(['rol' => User::ROL_ESTUDIANTE]);
        $solicitud = Solicitud::create([
            'estudiante_id' => $estudiante->id,
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T1,
            'modalidad' => Solicitud::MODALIDAD_CONVENCIONAL,
        ]);

        $doc = DocumentoSolicitud::create([
            'solicitud_id' => $solicitud->id,
            'subido_por' => $estudiante->id,
            'tipo_documento' => DocumentoSolicitud::TIPO_FICHA_FIRMADA,
            'nombre_original' => 'ficha.pdf',
            'ruta_privada' => 'solicitudes/2026/ficha_1.pdf',
            'peso_kb' => 512,
        ]);

        $this->assertSame($solicitud->id, $doc->solicitud->id);

        $this->expectException(InvalidArgumentException::class);

        DocumentoSolicitud::create([
            'solicitud_id' => $solicitud->id,
            'subido_por' => $estudiante->id,
            'tipo_documento' => DocumentoSolicitud::TIPO_FICHA_FIRMADA,
            'nombre_original' => 'ficha_insegura.pdf',
            'ruta_privada' => 'public/solicitudes/ficha.pdf',
            'peso_kb' => 512,
        ]);
    }

    public function test_grupo_taller_restringe_modalidad_clinica_y_rango_de_2_a_3_integrantes(): void
    {
        $grupo = GrupoTaller::create([
            'nombre_grupo' => 'Equipo Clínica Alpha',
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T3,
        ]);

        $estudiantes = User::factory()->count(4)->create(['rol' => User::ROL_ESTUDIANTE]);
        $solicitudes = $estudiantes->map(fn (User $user) => Solicitud::create([
            'estudiante_id' => $user->id,
            'anio' => 2026,
            'trimestre' => Solicitud::TRIMESTRE_T3,
            'modalidad' => Solicitud::MODALIDAD_CLINICA,
        ]));

        // 1 integrante -> aún no cumple el mínimo de 2
        $grupo->estudiantes()->attach($estudiantes[0]->id, ['solicitud_id' => $solicitudes[0]->id]);
        $this->assertFalse($grupo->cumpleRangoIntegrantes());

        // 2 integrantes -> válido
        $grupo->estudiantes()->attach($estudiantes[1]->id, ['solicitud_id' => $solicitudes[1]->id]);
        $this->assertTrue($grupo->cumpleRangoIntegrantes());
        $this->assertTrue($grupo->tieneCupoDisponible());

        // 3 integrantes -> válido y lleno
        $grupo->estudiantes()->attach($estudiantes[2]->id, ['solicitud_id' => $solicitudes[2]->id]);
        $this->assertTrue($grupo->cumpleRangoIntegrantes());
        $this->assertFalse($grupo->tieneCupoDisponible());

        // 4to integrante -> debe lanzar excepción
        $this->expectException(InvalidArgumentException::class);
        $grupo->estudiantes()->attach($estudiantes[3]->id, ['solicitud_id' => $solicitudes[3]->id]);
    }
}

