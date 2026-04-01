# 🏛️ Sistema de Control de Visitas — Cárcel El Redentor
> Laravel 13 · Filament 5 · ADSO  
> Instructor: Juan Camilo Vanegas González

---

## ⚙️ Requisitos del Sistema

| Herramienta | Versión | Notas |
|-------------|---------|-------|
| PHP | **8.3.x Thread Safe x64** | Descargar VS16 x64 TS desde [windows.php.net](https://windows.php.net/download) |
| Composer | 2.x | [getcomposer.org](https://getcomposer.org) |
| XAMPP | Cualquier versión reciente | Para Apache y MySQL |
| Git | Cualquier versión | Para clonar el repositorio |

---

## 🔧 Extensiones PHP Requeridas

Abrir `C:\xampp\php\php.ini` y asegurarse que estas líneas estén **sin** `;` al inicio:

```ini
extension_dir = "ext"
extension=curl
extension=fileinfo
extension=gd
extension=intl
extension=mbstring
extension=mysqli
extension=openssl
extension=pdo_mysql
extension=zip
```

> ⚠️ **Importante:** Copiar el `php.ini` también a `C:\xampp\php\php.ini` para que PHP CLI lo detecte correctamente.

---

## 🚀 Instalación

### 1. Clonar el repositorio y crear tu rama

```bash
git clone <URL_DEL_REPO>
cd carcel_Laravel/carcel
git checkout -b tu_nombre
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar el entorno

```bash
copy .env.example .env
php artisan key:generate
```

Editar el `.env` con los datos de MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=carcel_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Crear la base de datos

Ir a `http://localhost/phpmyadmin` y crear una base de datos llamada `carcel_db`.

### 5. Ejecutar migraciones

```bash
php artisan migrate
```

### 6. Crear usuario administrador

```bash
php artisan tinker
```

Dentro de tinker ejecutar:

```php
\App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@gmail.com',
    'password' => bcrypt('admin123'),
    'identification_number' => '123456789',
    'role' => 'admin',
    'is_active' => true,
]);
```

### 7. Crear usuario guardia de prueba (opcional)

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Guardia Pérez',
    'email' => 'guardia@gmail.com',
    'password' => bcrypt('guardia123'),
    'identification_number' => '987654321',
    'role' => 'guard',
    'is_active' => true,
]);
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

Abrir en el navegador: `http://localhost:8000/admin`

---

## 📦 Paquetes Instalados

| Paquete | Versión | Función |
|---------|---------|---------|
| `laravel/framework` | ^13.0 | Framework base |
| `filament/filament` | ^5.4 | Panel de administración |
| `barryvdh/laravel-dompdf` | ^3.1 | Generación de PDFs |
| `maatwebsite/excel` | ^3.1 | Exportación a Excel |

---

## 🏗️ Arquitectura del Proyecto

### Modelos y Tablas

| Modelo | Tabla | Descripción |
|--------|-------|-------------|
| `User` | `users` | Guardias y administradores del sistema |
| `Prisoner` | `prisoners` | Prisioneros de la cárcel |
| `Visitor` | `visitors` | Visitantes registrados |
| `Visit` | `visits` | Registro de visitas realizadas |
| `LoginLog` | `login_logs` | Historial de inicios de sesión |

### Estructura de Campos

**users:** `id`, `name`, `email`, `password`, `identification_number`, `role` (admin/guard), `is_active`

**prisoners:** `id`, `full_name`, `birth_date`, `entry_date`, `crime`, `assigned_cell`

**visitors:** `id`, `full_name`, `identification_number`

**visits:** `id`, `prisoner_id`, `visitor_id`, `relationship`, `user_id`, `start_time`, `end_time`, `status` (pendiente/completada/cancelada)

**login_logs:** `id`, `user_id`, `login_at`

---

## 🎛️ Recursos Filament (Panel Admin)

Filament 5 organiza cada recurso en su propia carpeta con archivos separados para el formulario y la tabla. La estructura es:

```
app/Filament/
├── Pages/
│   └── ReporteDashboard.php        ← Página de reportes (solo admin)
└── Resources/
    ├── Prisoners/
    │   ├── PrisonerResource.php
    │   ├── Schemas/PrisonerForm.php
    │   └── Tables/PrisonersTable.php
    ├── Visitors/
    │   ├── VisitorResource.php
    │   ├── Schemas/VisitorForm.php
    │   └── Tables/VisitorsTable.php
    ├── Visits/
    │   ├── VisitResource.php
    │   ├── Schemas/VisitForm.php
    │   ├── Tables/VisitsTable.php
    │   └── Pages/
    │       ├── CreateVisit.php     ← Validación de duplicados y estado inicial
    │       └── EditVisit.php       ← Deshabilitado (no se puede editar)
    └── Users/
        ├── UserResource.php
        ├── Schemas/UserForm.php
        └── Tables/UsersTable.php
```

### Control de Acceso por Rol

El sistema usa el campo `role` del modelo `User` para controlar qué ve cada usuario:

| Recurso | Admin | Guardia |
|---------|-------|---------|
| Prisioneros | ❌ | ✅ |
| Visitantes | ❌ | ✅ |
| Visitas | ❌ | ✅ |
| Guardias | ✅ | ❌ |
| Reportes | ✅ | ❌ |

Cada Resource implementa `canAccess()`:

```php
// Solo guardias
public static function canAccess(): bool
{
    return auth()->user()->role === 'guard';
}

// Solo admin
public static function canAccess(): bool
{
    return auth()->user()->role === 'admin';
}
```

---

## 📋 Reglas de Negocio Implementadas

### Visitas
- Las visitas **solo se permiten los domingos** de **14:00 a 17:00**
- Un prisionero **no puede tener dos visitas activas** (pendiente o completada) en el mismo horario
- Si existe una visita **cancelada** en ese horario, sí se puede registrar una nueva
- **No se pueden registrar visitas en fechas pasadas**
- **No se pueden registrar visitas con más de 3 meses de anticipación**
- Las visitas se crean siempre con estado `pendiente`
- **No se pueden editar** una vez creadas — solo cambiar estado
- El guardia puede **cancelar** una visita pendiente desde la tabla con confirmación
- Una vez **cancelada**, no se puede revertir a ningún otro estado
- Cuando la hora de fin de una visita pasa, su estado cambia automáticamente a `completada`

### Estados de Visita

| Estado | Color | Descripción |
|--------|-------|-------------|
| `pendiente` | 🟡 Amarillo | Visita programada, aún no ocurrida |
| `completada` | 🟢 Verde | Visita realizada (automático al pasar la hora) |
| `cancelada` | 🔴 Rojo | Visita cancelada por el guardia (irreversible) |

### Visitantes
- El número de identificación debe ser **único** en el sistema
- Si ya existe un visitante con ese número, el sistema muestra error automáticamente

### Guardias
- Solo el **administrador** puede crear, editar o desactivar guardias
- Un guardia desactivado (`is_active = false`) no puede iniciar sesión
- Cada inicio de sesión queda registrado en `login_logs` con fecha y hora

---

## 📊 Reportes (Solo Administrador)

El administrador puede acceder a `/admin/reporte-dashboard` para:

1. **Filtrar visitas** por rango de fechas (Fecha Inicio y Fecha Fin)
2. **Ver resultados** en tabla directamente en el panel
3. **Exportar a Excel** — descarga un `.xlsx` con todos los datos del período
4. **Exportar a PDF** — descarga un `.pdf` con el reporte formateado

### Archivos de Reportes

| Archivo | Ubicación | Función |
|---------|-----------|---------|
| `ReporteDashboard.php` | `app/Filament/Pages/` | Página Filament con filtros y botones |
| `ReporteController.php` | `app/Http/Controllers/` | Controlador que genera PDF y Excel |
| `VisitasExport.php` | `app/Exports/` | Clase de exportación Excel (Maatwebsite) |
| `visitas_pdf.blade.php` | `resources/views/reportes/` | Vista HTML para el PDF (dompdf) |

### Rutas de Exportación

```php
// routes/web.php
Route::middleware(['auth'])->group(function () {
    Route::get('/reporte/excel', [ReporteController::class, 'exportarExcel'])->name('reporte.excel');
    Route::get('/reporte/pdf',   [ReporteController::class, 'exportarPdf'])->name('reporte.pdf');
});
```

> ⚠️ **Nota sobre el PDF:** El archivo `visitas_pdf.blade.php` debe guardarse en encoding **UTF-8 sin BOM**. En VS Code: `Ctrl+Shift+P` → `Save with Encoding` → `UTF-8`.

---

## 📝 Registro de Sesiones (Login Log)

Cada vez que un usuario inicia sesión, se registra automáticamente en la tabla `login_logs`.

Esto se implementó con un **Event Listener**:

- **Evento:** `Illuminate\Auth\Events\Login` (disparado automáticamente por Laravel)
- **Listener:** `App\Listeners\LogSuccessfulLogin`
- **Registro:** `AppServiceProvider` conecta el evento con el listener

```php
// app/Providers/AppServiceProvider.php
Event::listen(Login::class, LogSuccessfulLogin::class);
```

---

## 🗃️ Migraciones Importantes

### Migración de estado en visitas
Se agregó el campo `status` a la tabla `visits` mediante una migración independiente:

```bash
php artisan make:migration add_status_to_visits_table
```

```php
// En up():
$table->enum('status', ['pendiente', 'completada', 'cancelada'])
      ->default('pendiente')
      ->after('end_time');
```

> ⚠️ Si se ejecuta `migrate:fresh`, recordar recrear los usuarios admin y guardia con tinker (ver pasos 6 y 7).

---

## ✅ Estado Actual del Proyecto

### Completado
- [x] PHP 8.3.30 instalado y configurado en XAMPP
- [x] Extensiones PHP habilitadas (curl, gd, intl, mbstring, mysqli, openssl, pdo_mysql, zip)
- [x] php.ini correctamente cargado por PHP CLI
- [x] Proyecto clonado y rama individual creada
- [x] `composer install` ejecutado sin errores
- [x] `.env` configurado con MySQL
- [x] Base de datos `carcel_db` creada
- [x] Migraciones ejecutadas (users, prisoners, visitors, visits, login_logs)
- [x] Campo `status` agregado a tabla `visits` (pendiente/completada/cancelada)
- [x] Filament 5.4 instalado y panel `/admin` funcionando
- [x] `barryvdh/laravel-dompdf` instalado
- [x] `maatwebsite/excel` instalado
- [x] Usuario admin creado en base de datos
- [x] Resource de Prisioneros — CRUD completo en español con validaciones
- [x] Resource de Visitantes — CRUD completo, identificación única
- [x] Resource de Visitas — formulario con selectores de nombres y validaciones completas
- [x] Validación: solo domingos de 14:00 a 17:00
- [x] Validación: no fechas pasadas
- [x] Validación: máximo 3 meses a futuro
- [x] Validación: no duplicados de horario por prisionero (excluyendo canceladas)
- [x] Estados de visita con badge de colores (pendiente/completada/cancelada)
- [x] Botón "Cancelar visita" en tabla — solo visible en visitas pendientes
- [x] Cancelación irreversible con confirmación modal
- [x] Auto-completar visitas cuya hora de fin ya pasó
- [x] Visitas no editables una vez creadas
- [x] Resource de Guardias — CRUD con toggle activo/inactivo y badges de rol
- [x] Control de acceso por rol (admin vs guardia) mediante `canAccess()`
- [x] Registro automático de login en tabla `login_logs`
- [x] Listener `LogSuccessfulLogin` conectado en `AppServiceProvider`
- [x] Modelo `LoginLog` creado con relación a `User`
- [x] Página de Reportes con filtros por rango de fechas
- [x] Exportación a Excel funcional con encabezados y estilos
- [x] Exportación a PDF funcional (dompdf con DejaVu Sans, UTF-8 sin BOM)

### Pendiente — rama `cris`
- [ ] Diseño mejorado con Tailwind

---

## 👥 Equipo

| Integrante | Rama | Responsabilidad |
|------------|------|-----------------|
| Cristian | `cris` | Panel admin, reportes PDF/Excel, diseño |
| ... | ... | ... |

---

> Proyecto ADSO · Cárcel El Redentor · Laravel 13