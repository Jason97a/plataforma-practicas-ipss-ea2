<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;

class DocumentoSolicitud extends Model
{
    protected $table = 'documentos_solicitud';

    /**
     * Disco privado configurado en config/filesystems.php -> storage/app/private.
     */
    public const DISCO_PRIVADO = 'local';

    public const MIME_PDF = 'application/pdf';

    /**
     * Peso máximo permitido por documento según ISO 25010 (10 MB = 10240 KB).
     */
    public const MAX_PESO_KB = 10240;

    public const TIPO_FICHA_FIRMADA = 'ficha_inscripcion_firmada';

    public const TIPO_CERTIFICADO_ANTIGUEDAD = 'certificado_antiguedad_laboral';

    public const TIPO_CERTIFICADO_COTIZACIONES = 'certificado_cotizaciones_12m';

    public const TIPO_RTM_INICIAL = 'rtm_inicial';

    public const TIPO_CARTA_SEGURO_ESCOLAR = 'carta_seguro_escolar';

    public const TIPO_COMPROBANTE_INSCRIPCION = 'comprobante_inscripcion';

    public const TIPOS_DOCUMENTO = [
        self::TIPO_FICHA_FIRMADA,
        self::TIPO_CERTIFICADO_ANTIGUEDAD,
        self::TIPO_CERTIFICADO_COTIZACIONES,
        self::TIPO_RTM_INICIAL,
        self::TIPO_CARTA_SEGURO_ESCOLAR,
        self::TIPO_COMPROBANTE_INSCRIPCION,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'solicitud_id',
        'subido_por',
        'tipo_documento',
        'nombre_original',
        'ruta_privada',
        'mime_type',
        'peso_kb',
        'version',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'peso_kb' => 'integer',
            'version' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $documento): void {
            // Regla de Negocio #3 (ISO 25010): Jamás permitir rutas en directorio público
            $rutaNormalizada = ltrim(str_replace('\\', '/', $documento->ruta_privada), '/');

            if (str_starts_with($rutaNormalizada, 'public/') || str_contains($rutaNormalizada, '..')) {
                throw new InvalidArgumentException(
                    'Los documentos deben almacenarse exclusivamente en el disco privado (storage/app/private).'
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
    public function subidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'subido_por');
    }

    public function existeEnDiscoPrivado(): bool
    {
        return Storage::disk(self::DISCO_PRIVADO)->exists($this->ruta_privada);
    }
}

