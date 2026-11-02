# Changelog

Todos los cambios notables en la **Plataforma de Gestión de Prácticas y Titulación — IPSS** serán documentados en este archivo.

El formato está basado en [Keep a Changelog](https://keepachangelog.com/es-ES/1.1.0/), y este proyecto adhiere a [Semantic Versioning](https://semver.org/lang/es/).

> **Guía rápida para el equipo (antes de abrir un Pull Request a `develop`):**
> Agrega una entrada en la versión correspondiente (o en `[Unreleased]` si está en curso) indicando la fecha (`YYYY-MM-DD`), la rama asociada y las categorías aplicables (`Agregado`, `Cambiado`, `Corregido`, `Seguridad`).

---

## [0.2.0] - 2026-11-02

### Agregado
- **Modelos Eloquent y Relaciones (`feat/modelos-eloquent`):**
  - `App\Models\Solicitud`: relaciones con estudiante, supervisor, revisor, documentos, historial y grupos de taller; scopes de filtrado (`filtrarPeriodo`, `porEstado`, `porModalidad`) y regla automática que fuerza `modalidad_taller` (`individual` en Convencional/Convalidación y `grupal` en Clínica Tecnológica).
  - `App\Models\DocumentoSolicitud`: constantes de tipos documentales, disco privado (`storage/app/private`), límite de 10 MB y validación automática contra rutas públicas o *path traversal* (ISO/IEC 25010).
  - `App\Models\HistorialEstado`: trazabilidad de estados con validación obligatoria de `motivo_u_observacion` al pasar a `con_observaciones` o `rechazada`.
  - `App\Models\GrupoTaller` y `App\Models\GrupoTallerUser`: modelo de equipos y pivote ternario con restricción estricta de **mínimo 2 y máximo 3 integrantes** exclusivo para modalidad `clinica_tecnologica`.
  - `Tests\Feature\EloquentModelsTest`: suite de pruebas automatizadas PHPUnit cubriendo roles, relaciones y reglas críticas de negocio.
  - `CHANGELOG.md`: registro histórico de versiones y cambios para facilitar la revisión de Pull Requests del equipo.

### Cambiado
- `App\Models\User`: extendido con atributos institucionales (`rut`, `rol`, `carrera`, `nivel_formativo`, `microsoft_id`, `activo`), relaciones Eloquent y helpers de autorización por rol (`esEstudiante()`, `esCoordinador()`, `esSupervisor()`, `esAdmin()`, `tieneRol()`).
- `AGENTS.md`: documentado el contexto técnico del proyecto y las reglas críticas de negocio del IPSS.

---

## [0.1.1] - 2026-11-01

### Agregado
- `.github/CODEOWNERS`: definición de revisores obligatorios para Pull Requests hacia ramas protegidas.

### Cambiado
- `README.md`: actualización completa con alcance funcional (RF), arquitectura del sistema, guía de instalación rápida con Docker y flujo de trabajo Gitflow.
- `.env.example`: estandarización de variables de entorno locales para conexión con MySQL 8.0 en puerto `3307` (`ipss_practicas_db`) y disco privado por defecto.

---

## [0.1.0] - 2026-11-01

### Agregado
- Inicialización del proyecto base en Laravel con estructura MVC y configuración de pruebas PHPUnit.
- Integración de dependencias clave en `composer.json`: `laravel/socialite`, `socialiteproviders/microsoft` (SSO Microsoft 365) y `barryvdh/laravel-dompdf` (emisión de Seguro Escolar en PDF).
- `docker-compose.yml`: contenedor local de MySQL Server 8.0 expuesto en el puerto `3307`.
- `database/migrations/2026_11_01_000003_create_ipss_practicas_schema.php`: esquema relacional completo de la plataforma (`users` extendido, `solicitudes`, `documentos_solicitud`, `historial_estados`, `grupos_taller` y `grupo_taller_user`) con claves foráneas e índices compuestos.
- `.github/pull_request_template.md`: plantilla estandarizada de verificación funcional y de seguridad para Pull Requests.
