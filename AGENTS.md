# Contexto del Proyecto: Plataforma de Gestión de Prácticas IPSS

## Stack y Entorno
- Framework: Laravel 11 (PHP 8.2+) con vistas Blade.
- Base de datos: MySQL 8.0 en Docker local expuesta en el puerto `3307` (`DB_DATABASE=ipss_practicas_db`).
- Paquetes instalados: `laravel/socialite` + `socialiteproviders/microsoft` (SSO MS365), `barryvdh/laravel-dompdf` (Seguro Escolar).

## Reglas de Negocio Críticas (No violar)
1. **3 Modalidades de Práctica:**
   - `convencional`: Exige datos de empresa, supervisor, 360 horas y PDF de ficha firmada. Al pasar a estado `aprobada`, genera automáticamente PDF de Seguro Escolar y fuerza `modalidad_taller = 'individual'`.
   - `convalidacion`: Exige certificado de antigüedad/cotizaciones (mínimo 12 meses) y RTM inicial. NO genera seguro escolar, activa recordatorio EVA y fuerza `modalidad_taller = 'individual'`.
   - `clinica_tecnologica`: Omite datos de empresa externa. Fuerza `modalidad_taller = 'grupal'` permitiendo crear/unirse a grupos en Taller de Integración de **mínimo 2 y máximo 3 integrantes**.
2. **Flujo de Estados (`solicitudes.estado`):**
   - `enviada` -> `en_revision` -> `con_observaciones` / `rechazada` -> `aprobada` (o `anulada`).
   - Pasar a `con_observaciones` o `rechazada` exige obligatoriamente registrar texto en `historial_estados.motivo_u_observacion`.
3. **Seguridad (ISO 25010):**
   - Todos los PDFs subidos por estudiantes deben guardarse en el disco privado (`storage/app/private`), jamás en `public/`.
   - Usar siempre Eloquent ORM y validación de FormRequest (`mimetypes:application/pdf`, máx 10MB).