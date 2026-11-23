<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROL_ESTUDIANTE = 'estudiante';

    public const ROL_COORDINADOR = 'coordinador';

    public const ROL_SUPERVISOR = 'supervisor';

    public const ROL_ADMIN = 'admin';

    public const ROLES = [
        self::ROL_ESTUDIANTE,
        self::ROL_COORDINADOR,
        self::ROL_SUPERVISOR,
        self::ROL_ADMIN,
    ];

    public const NIVEL_TECNICO = 'tecnico';

    public const NIVEL_INGENIERIA = 'ingenieria';

    public const NIVELES_FORMATIVOS = [
        self::NIVEL_TECNICO,
        self::NIVEL_INGENIERIA,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'rut',
        'name',
        'email',
        'rol',
        'carrera',
        'nivel_formativo',
        'password',
        'microsoft_id',
        'activo',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    /**
     * Solicitudes postuladas por este usuario como estudiante.
     *
     * @return HasMany<Solicitud, $this>
     */
    public function solicitudes(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'estudiante_id');
    }

    /**
     * Solicitudes asignadas a este usuario como supervisor académico/institucional.
     *
     * @return HasMany<Solicitud, $this>
     */
    public function solicitudesSupervisadas(): HasMany
    {
        return $this->hasMany(Solicitud::class, 'supervisor_id');
    }


   /** Solicitudes revisadas o gestionadas por el coordinador o administrador. */
    public function solicitudesRevisadas(): HasMany
    {
    return $this->hasMany(Solicitud::class, 'revisado_por');
    }

    /**
     * Documentos PDF cargados al repositorio privado por este usuario.
     *
     * @return HasMany<DocumentoSolicitud, $this>
     */
    public function documentosSubidos(): HasMany
    {
        return $this->hasMany(DocumentoSolicitud::class, 'subido_por');
    }

    /**
     * Cambios de estado registrados por este usuario en la trazabilidad.*
     * @return HasMany<HistorialEstado, $this>
     */
    public function historialEstadosRegistrados(): HasMany
    {
        return $this->hasMany(HistorialEstado::class, 'usuario_id');
    }

   /**
    * Grupos de Taller de Integración en los que participa el estudiante.
    *
    * @return BelongsToMany<GrupoTaller, $this, GrupoTallerUser>
    */
    public function gruposTaller(): BelongsToMany
    {
        return $this->belongsToMany(GrupoTaller::class, 'grupo_taller_user', 'estudiante_id', 'grupo_taller_id')
            ->using(GrupoTallerUser::class)
            ->withPivot(['id', 'solicitud_id'])
            ->withTimestamps();
    }

    public function esEstudiante(): bool
    {
        return $this->rol === self::ROL_ESTUDIANTE;
    }

    public function esCoordinador(): bool
    {
        return $this->rol === self::ROL_COORDINADOR;
    }

    public function esSupervisor(): bool
    {
        return $this->rol === self::ROL_SUPERVISOR;
    }

    public function esAdmin(): bool
    {
        return $this->rol === self::ROL_ADMIN;
    }

    public function tieneRol(string ...$roles): bool
    {
        return in_array($this->rol, $roles, true);
    }
}
