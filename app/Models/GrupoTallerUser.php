<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use InvalidArgumentException;

class GrupoTallerUser extends Pivot
{
    protected $table = 'grupo_taller_user';

    public $incrementing = true;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'grupo_taller_id',
        'estudiante_id',
        'solicitud_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $pivote): void {
            $solicitud = Solicitud::find($pivote->solicitud_id);

            if (! $solicitud || ! $solicitud->permiteGrupoTaller()) {
                throw new InvalidArgumentException(
                    'Solo las solicitudes en modalidad Clínica Tecnológica pueden integrarse a grupos de Taller de Integración.'
                );
            }

            if ($solicitud->estudiante_id !== (int) $pivote->estudiante_id) {
                throw new InvalidArgumentException(
                    'La solicitud asociada no pertenece al estudiante que intenta unirse al grupo.'
                );
            }

            $grupo = GrupoTaller::find($pivote->grupo_taller_id);

            if ($grupo && ! $grupo->tieneCupoDisponible()) {
                throw new InvalidArgumentException(
                    'El grupo ya alcanzó el máximo permitido de 3 integrantes.'
                );
            }
        });
    }

    /**
     * @return BelongsTo<GrupoTaller, $this>
     */
    public function grupoTaller(): BelongsTo
    {
        return $this->belongsTo(GrupoTaller::class, 'grupo_taller_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function estudiante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }

    /**
     * @return BelongsTo<Solicitud, $this>
     */
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }
}

