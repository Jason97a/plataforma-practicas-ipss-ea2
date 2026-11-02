<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class GrupoTaller extends Model
{
    protected $table = 'grupos_taller';

    public const MIN_INTEGRANTES = 2;

    public const MAX_INTEGRANTES = 3;

    public const ESTADO_EN_FORMACION = 'en_formacion';

    public const ESTADO_COMPLETO = 'completo';

    public const ESTADO_VALIDADO = 'validado';

    public const ESTADOS_GRUPO = [
        self::ESTADO_EN_FORMACION,
        self::ESTADO_COMPLETO,
        self::ESTADO_VALIDADO,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'nombre_grupo',
        'anio',
        'trimestre',
        'min_integrantes',
        'max_integrantes',
        'estado_grupo',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'anio' => 'integer',
            'min_integrantes' => 'integer',
            'max_integrantes' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $grupo): void {
            $grupo->min_integrantes ??= self::MIN_INTEGRANTES;
            $grupo->max_integrantes ??= self::MAX_INTEGRANTES;

            // Regla de Negocio #1: Taller de Integración grupal exige estrictamente mín 2 y máx 3 integrantes
            if ($grupo->min_integrantes < self::MIN_INTEGRANTES || $grupo->max_integrantes > self::MAX_INTEGRANTES || $grupo->min_integrantes > $grupo->max_integrantes) {
                throw new InvalidArgumentException(
                    'Los grupos de Taller de Integración deben tener un mínimo de 2 y un máximo de 3 integrantes.'
                );
            }
        });
    }

    /**
     * @return BelongsToMany<User, $this, GrupoTallerUser>
     */
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'grupo_taller_user', 'grupo_taller_id', 'estudiante_id')
            ->using(GrupoTallerUser::class)
            ->withPivot(['id', 'solicitud_id'])
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Solicitud, $this, GrupoTallerUser>
     */
    public function solicitudes(): BelongsToMany
    {
        return $this->belongsToMany(Solicitud::class, 'grupo_taller_user', 'grupo_taller_id', 'solicitud_id')
            ->using(GrupoTallerUser::class)
            ->withPivot(['id', 'estudiante_id'])
            ->withTimestamps();
    }

    /**
     * @return HasMany<GrupoTallerUser, $this>
     */
    public function membresias(): HasMany
    {
        return $this->hasMany(GrupoTallerUser::class, 'grupo_taller_id');
    }

    public function cantidadIntegrantes(): int
    {
        return $this->membresias()->count();
    }

    public function tieneCupoDisponible(): bool
    {
        return $this->cantidadIntegrantes() < ($this->max_integrantes ?? self::MAX_INTEGRANTES);
    }

    public function cumpleRangoIntegrantes(): bool
    {
        $total = $this->cantidadIntegrantes();
        $min = $this->min_integrantes ?? self::MIN_INTEGRANTES;
        $max = $this->max_integrantes ?? self::MAX_INTEGRANTES;

        return $total >= $min && $total <= $max;
    }
}

