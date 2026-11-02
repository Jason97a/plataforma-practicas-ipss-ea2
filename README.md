# Plataforma de Gestión de Prácticas y Titulación — IPSS

Plataforma web centralizada para la gestión, validación documental y trazabilidad del proceso de **Práctica Profesional** (Convencional, Convalidación y Clínica Tecnológica) y su articulación normativa con la asignatura **Taller de Integración** del **Instituto Profesional San Sebastián (IPSS)**.

---

## 1. Alcance Funcional y Reglas de Negocio

El sistema automatiza el flujo para un volumen aproximado de **250 estudiantes por trimestre** (Trimestre 1 en carreras técnicas y Trimestre 3 en ingenierías), resolviendo las siguientes reglas institucionales:

* **Autenticación Institucional (RF_01):** Inicio de sesión único (SSO) integrado con **Microsoft 365** (`@ipss.cl`) y control de acceso basado en roles (*Estudiante, Coordinador, Supervisor, Administrador*).
* **Modalidades de Postulación (RF_04, RF_08):**
  * **Práctica Convencional:** Registro de centro de práctica, supervisor, 360 horas y carga de ficha firmada en PDF. Al aprobarse, emite automáticamente la **Carta de Presentación (Seguro Escolar)** en PDF y bloquea el Taller de Integración en modalidad **individual**.
  * **Convalidación (Laboral / Emprendimiento):** Carga de certificado de antigüedad/cotizaciones (mínimo 12 meses) y RTM inicial. Omite la emisión de seguro escolar, habilita recordatorio para RTM final en EVA y asigna modalidad **individual** en el Taller de Integración.
  * **Clínica Tecnológica:** Formulario simplificado sin datos de empresa externa. Habilita la conformación de equipos en el Taller de Integración restringidos estrictamente a **mínimo 2 y máximo 3 integrantes**.
* **Trazabilidad en Tiempo Real (RF_09, RF_10):** Seguimiento visual del flujo de estados (`Enviada` ➔ `En revisión` ➔ `Con observaciones` / `Rechazada` ➔ `Aprobada`), exigiendo motivo obligatorio de retroalimentación ante cualquier observación o rechazo.

---

## 2. Stack Tecnológico y Seguridad

* **Backend:** PHP 8.2+ / Laravel 11 (Arquitectura MVC)
* **Base de Datos:** MySQL Server 8.0 (Contenedor Docker local en puerto `3307`)
* **Paquetes Clave:**
  * `laravel/socialite` + `socialiteproviders/microsoft` (SSO Microsoft 365)
  * `barryvdh/laravel-dompdf` (Emisión automática de Seguro Escolar)
* **Lineamientos de Seguridad (ISO/IEC 25010):**
  * **Repositorio Documental Privado:** El 100% de los documentos PDF se almacenan fuera del directorio público (`storage/app/private`) y se sirven mediante *streams* autenticados y autorizados por rol.
  * **Separación de Ambientes:** Desarrollo validado en entorno local; la revisión de seguridad, despliegue en ambiente **QA** y paso a **Producción** son gestionados exclusivamente por el **Área de TI del IPSS**.

---

## 3. Guía de Instalación Rápida (Para el Equipo de Desarrollo)

Sigue estos pasos para levantar el entorno local completo en menos de 3 minutos:

### Requisitos previos
* PHP >= 8.2, Composer y Docker (con Docker Compose).

### Paso a paso
```bash
# 1. Clonar el repositorio y entrar al directorio
git clone [https://github.com/alvarogonzalez-ipss/plataforma-practicas-ipss.git](https://github.com/alvarogonzalez-ipss/plataforma-practicas-ipss.git)
cd plataforma-practicas-ipss

# 2. Cambiarse a la rama de integración 'develop'
git checkout develop

# 3. Instalar dependencias de PHP
composer install

# 4. Configurar variables de entorno locales
cp .env.example .env
php artisan key:generate

# 5. Levantar el contenedor de MySQL 8.0 (Expuesto en el puerto 3307)
docker compose up -d

# 6. Ejecutar las migraciones del esquema IPSS
php artisan migrate

# 7. Iniciar el servidor de desarrollo local
php artisan serve
```
La aplicación estará disponible en `http://localhost:8000`.

---

## 4. Flujo de Trabajo en Git y Reglas de Ramas (Gitflow)

Las ramas `main` y `develop` se encuentran protegidas. **Está bloqueado el `git push` directo** a ambas ramas; todo cambio debe ingresar mediante un **Pull Request (PR)** revisado y aprobado por el administrador del repositorio (`@alvarogonzalez-ipss`).

1. Antes de empezar una tarea, actualiza tu rama `develop`:
   ```bash
   git checkout develop
   git pull origin develop
   ```
2. Crea una rama para tu requerimiento o vista siguiendo la convención:
   * `feat/nombre-funcionalidad` (ej. `feat/ui-formulario-convencional`)
   * `fix/descripcion-correccion` (ej. `fix/validacion-peso-pdf`)
   ```bash
   git checkout -b feat/mi-nueva-funcionalidad
   ```
3. Realiza tus commits y sube tu rama al repositorio:
   ```bash
   git add .
   git commit -m "feat(solicitudes): agregar vista de formulario practica convencional"
   git push -u origin feat/mi-nueva-funcionalidad
   ```
4. En GitHub, abre un **Pull Request** apuntando desde tu rama hacia **`develop`** (nunca hacia `main`), completa la plantilla de verificación y solicita la revisión.

---

## 5. Estructura de Directorios Clave por Rol

* `database/migrations/` y `database/seeders/` — Esquema relacional MySQL 8.0 y datos semilla.
* `app/Models/` y `app/Services/` — Modelos Eloquent, máquina de estados y reglas de grupos (2-3 integrantes).
* `resources/views/estudiante/` — Vistas de formularios por modalidad, barra de estado y grupos de taller.
* `resources/views/coordinador/` — Bandeja de revisión, filtros trimestrales (T1/T3) y resolución de solicitudes.
* `resources/views/pdf/` — Plantillas Blade para generación automática de Seguro Escolar y comprobantes.
* `storage/app/private/` — Almacenamiento aislado de documentos PDF (ignorado en Git).