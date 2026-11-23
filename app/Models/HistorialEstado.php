<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class HistorialEstado extends Model
{
    protected $table = 'historial_estados';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'solicitud_id',
        'usuario_id',
        'estado_anterior',
        'estado_nuevo',
        'motivo_u_observacion',
    ];

    protected static function booted(): void
    {
        static::saving(function (self $historial): void {
            // Solicitar un motivo cuando la solicitud tenga observaciones o sea rechazada.
            if (Solicitud::requiereMotivo($historial->estado_nuevo) && blank($historial->motivo_u_observacion)) {
                throw new InvalidArgumentException(
                    "El campo motivo_u_observacion es obligatorio al pasar al estado '{$historial->estado_nuevo}'."
                );
            }
        });
    }

    /**
     * @return BelongsTo<Solicitud, $this>
     */
    public function solicitud(): BelongsTo
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}

