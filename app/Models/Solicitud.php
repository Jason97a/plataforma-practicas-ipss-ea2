<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    public const MODALIDAD_CONVENCIONAL = 'convencional';

    public const MODALIDAD_CONVALIDACION = 'convalidacion';

    public const MODALIDAD_CLINICA = 'clinica_tecnologica';

    public const MODALIDADES = [
        self::MODALIDAD_CONVENCIONAL,
        self::MODALIDAD_CONVALIDACION,
        self::MODALIDAD_CLINICA,
    ];

    public const ESTADO_ENVIADA = 'enviada';

    public const ESTADO_EN_REVISION = 'en_revision';

    public const ESTADO_CON_OBSERVACIONES = 'con_observaciones';

    public const ESTADO_RECHAZADA = 'rechazada';

    public const ESTADO_APROBADA = 'aprobada';

    public const ESTADO_ANULADA = 'anulada';

    public const ESTADOS = [
        self::ESTADO_ENVIADA,
        self::ESTADO_EN_REVISION,
        self::ESTADO_CON_OBSERVACIONES,
        self::ESTADO_RECHAZADA,
        self::ESTADO_APROBADA,
        self::ESTADO_ANULADA,
    ];

    public const ESTADOS_CON_MOTIVO_OBLIGATORIO = [
        self::ESTADO_CON_OBSERVACIONES,
        self::ESTADO_RECHAZADA,
    ];

    public const TRIMESTRE_T1 = 'T1';

    public const TRIMESTRE_T2 = 'T2';

    public const TRIMESTRE_T3 = 'T3';

    public const TRIMESTRES = [
        self::TRIMESTRE_T1,
        self::TRIMESTRE_T2,
        self::TRIMESTRE_T3,
    ];

    public const TALLER_INDIVIDUAL = 'individual';

    public const TALLER_GRUPAL = 'grupal';

    public const HORAS_CONVENCIONAL = 360;

    public const MESES_MINIMOS_CONVALIDACION = 12;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'estudiante_id',
        'supervisor_id',
        'revisado_por',
        'anio',
        'trimestre',
        'modalidad',
        'estado',
        'empresa_nombre',
        'empresa_rut',
        'supervisor_empresa_nombre',
        'supervisor_empresa_email',
        'supervisor_empresa_telefono',
        'funciones_declaradas',
        'horas_requeridas',
        'meses_experiencia_declarados',
        'modalidad_taller',
        'seguro_escolar_emitido',
        'recordatorio_eva_enviado',
        'observacion_actual',
        'fecha_envio',
        'fecha_resolucion',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'anio' => 'integer',
            'horas_requeridas' => 'integer',
            'meses_experiencia_declarados' => 'integer',
            'seguro_escolar_emitido' => 'boolean',
            'recordatorio_eva_enviado' => 'boolean',
            'fecha_envio' => 'datetime',
            'fecha_resolucion' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $solicitud): void {
            // Regla de Negocio #1: Forzar modalidad_taller según la modalidad de práctica
            $solicitud->modalidad_taller = $solicitud->modalidad === self::MODALIDAD_CLINICA
                ? self::TALLER_GRUPAL
                : self::TALLER_INDIVIDUAL;
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /**
     * @return HasMany<DocumentoSolicitud, $this>
     */
    public function documentos(): HasMany
    {
        return $this->hasMany(DocumentoSolicitud::class, 'solicitud_id');
    }

    /**
     * @return HasMany<HistorialEstado, $this>
     */
    public function historialEstados(): HasMany
    {
        return $this->hasMany(HistorialEstado::class, 'solicitud_id')->latest('id');
    }

    /**
     * @return HasOne<GrupoTallerUser, $this>
     */
    public function membresiaGrupoTaller(): HasOne
    {
        return $this->hasOne(GrupoTallerUser::class, 'solicitud_id');
    }

    /**
     * @return BelongsToMany<GrupoTaller, $this, GrupoTallerUser>
     */
    public function gruposTaller(): BelongsToMany
    {
        return $this->belongsToMany(GrupoTaller::class, 'grupo_taller_user', 'solicitud_id', 'grupo_taller_id')
            ->using(GrupoTallerUser::class)
            ->withPivot(['id', 'estudiante_id'])
            ->withTimestamps();
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeFiltrarPeriodo(Builder $query, int $anio, string $trimestre): Builder
    {
        return $query->where('anio', $anio)->where('trimestre', $trimestre);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePorEstado(Builder $query, string $estado): Builder
    {
        return $query->where('estado', $estado);
    }

    /**
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePorModalidad(Builder $query, string $modalidad): Builder
    {
        return $query->where('modalidad', $modalidad);
    }

    public function esConvencional(): bool
    {
        return $this->modalidad === self::MODALIDAD_CONVENCIONAL;
    }

    public function esConvalidacion(): bool
    {
        return $this->modalidad === self::MODALIDAD_CONVALIDACION;
    }

    public function esClinicaTecnologica(): bool
    {
        return $this->modalidad === self::MODALIDAD_CLINICA;
    }

    public function permiteGrupoTaller(): bool
    {
        return $this->esClinicaTecnologica() && $this->modalidad_taller === self::TALLER_GRUPAL;
    }

    public static function requiereMotivo(string $estado): bool
    {
        return in_array($estado, self::ESTADOS_CON_MOTIVO_OBLIGATORIO, true);
    }
}

