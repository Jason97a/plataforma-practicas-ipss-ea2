<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Extender tabla users para perfiles IPSS y SSO MS365 (RF_01, RF_02, RF_03)
        Schema::table('users', function (Blueprint $table) {
            $table->string('rut', 15)->nullable()->unique()->after('id');
            $table->enum('rol', ['estudiante', 'coordinador', 'supervisor', 'admin'])
                  ->default('estudiante')->after('email');
            $table->string('carrera', 120)->nullable()->after('rol');
            $table->enum('nivel_formativo', ['tecnico', 'ingenieria'])->nullable()->after('carrera');
            $table->string('microsoft_id')->nullable()->unique()->after('password');
            $table->boolean('activo')->default(true)->after('microsoft_id');
            $table->string('password')->nullable()->change();
        });

        // 2. Tabla principal de Solicitudes de Práctica (RF_04 a RF_12 / HU-01 a HU-04)
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->nullOnDelete();

            // Trimestre 1 (Técnicos) o Trimestre 3 (Ingenierías)
            $table->unsignedSmallInteger('anio')->default(2026);
            $table->enum('trimestre', ['T1', 'T2', 'T3']);
            $table->enum('modalidad', ['convencional', 'convalidacion', 'clinica_tecnologica']);

            // Flujo de estados institucional (RF_09)
            $table->enum('estado', [
                'enviada',
                'en_revision',
                'con_observaciones',
                'rechazada',
                'aprobada',
                'anulada'
            ])->default('enviada');

            // Datos Centro de Práctica / Empresa (Obligatorios en Convencional/Convalidación, nulos en Clínica)
            $table->string('empresa_nombre', 150)->nullable();
            $table->string('empresa_rut', 20)->nullable();
            $table->string('supervisor_empresa_nombre', 120)->nullable();
            $table->string('supervisor_empresa_email', 120)->nullable();
            $table->string('supervisor_empresa_telefono', 30)->nullable();
            $table->text('funciones_declaradas')->nullable();
            $table->unsignedSmallInteger('horas_requeridas')->default(360);
            $table->unsignedSmallInteger('meses_experiencia_declarados')->nullable(); // Mín 12 en Convalidación

            // Automatizaciones y Reglas de Negocio (HU-01, HU-02, HU-05)
            $table->enum('modalidad_taller', ['individual', 'grupal'])->default('individual');
            $table->boolean('seguro_escolar_emitido')->default(false);
            $table->boolean('recordatorio_eva_enviado')->default(false);
            $table->text('observacion_actual')->nullable();

            $table->timestamp('fecha_envio')->useCurrent();
            $table->timestamp('fecha_resolucion')->nullable();
            $table->timestamps();

            // Índices compuestos para soportar los 250 usuarios en cierre de plazo (RNF_01)
            $table->index(['anio', 'trimestre', 'estado', 'modalidad'], 'idx_solicitudes_filtro_coord');
            $table->index(['estudiante_id', 'estado'], 'idx_solicitudes_estudiante');
        });

        // 3. Repositorio Documental Privado (RF_08, RNF_02)
        Schema::create('documentos_solicitud', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->foreignId('subido_por')->constrained('users')->restrictOnDelete();
            $table->enum('tipo_documento', [
                'ficha_inscripcion_firmada',
                'certificado_antiguedad_laboral',
                'certificado_cotizaciones_12m',
                'rtm_inicial',
                'carta_seguro_escolar',
                'comprobante_inscripcion'
            ]);
            $table->string('nombre_original', 255);
            $table->string('ruta_privada', 255); // En storage/app/private/
            $table->string('mime_type', 60)->default('application/pdf');
            $table->unsignedInteger('peso_kb');
            $table->unsignedSmallInteger('version')->default(1);
            $table->timestamps();
        });

        // 4. Trazabilidad e Historial de Estados y Evaluaciones (RF_09, RF_10)
        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->restrictOnDelete();
            $table->string('estado_anterior', 30)->nullable();
            $table->string('estado_nuevo', 30);
            $table->text('motivo_u_observacion')->nullable(); // Obligatorio al rechazar u observar
            $table->timestamps();
        });

        // 5. Grupos para Taller de Integración - Regla 2 a 3 integrantes en Clínica (HU-03, HU-05)
        Schema::create('grupos_taller', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_grupo', 100);
            $table->unsignedSmallInteger('anio')->default(2026);
            $table->enum('trimestre', ['T1', 'T2', 'T3']);
            $table->unsignedTinyInteger('min_integrantes')->default(2);
            $table->unsignedTinyInteger('max_integrantes')->default(3);
            $table->enum('estado_grupo', ['en_formacion', 'completo', 'validado'])->default('en_formacion');
            $table->timestamps();
        });

        Schema::create('grupo_taller_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_taller_id')->constrained('grupos_taller')->cascadeOnDelete();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
            $table->timestamps();

            // Un estudiante solo puede estar en un grupo por solicitud
            $table->unique(['estudiante_id', 'solicitud_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_taller_user');
        Schema::dropIfExists('grupos_taller');
        Schema::dropIfExists('historial_estados');
        Schema::dropIfExists('documentos_solicitud');
        Schema::dropIfExists('solicitudes');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rut', 'rol', 'carrera', 'nivel_formativo', 'microsoft_id', 'activo']);
        });
    }
};
