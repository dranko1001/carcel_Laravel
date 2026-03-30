# 🏛️ Sistema de Control de Visitas — Cárcel El Redentor
> Laravel 13 · Filament 5 · Spatie Permission · ADSO  
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

### 7. Iniciar el servidor

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
| `spatie/laravel-permission` | ^7.2 | Roles y permisos (admin / guardia) |
| `barryvdh/laravel-dompdf` | ^3.1 | Generación de PDFs |
| `maatwebsite/excel` | ^3.1 | Exportación a Excel |

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
- [x] Migraciones ejecutadas (users, prisoners, visitors, visits, login_logs, permisos)
- [x] Spatie Laravel Permission instalado y migrado
- [x] Filament 5.4 instalado y panel `/admin` funcionando
- [x] `barryvdh/laravel-dompdf` instalado
- [x] `maatwebsite/excel` instalado
- [x] Usuario admin creado en base de datos

### Pendiente — rama `cris`
- [ ] Gestión de guardias desde panel admin (Filament Resource)
- [ ] Dashboard con filtros por rango de fechas
- [ ] Exportar reporte de visitas a Excel
- [ ] Exportar historial de visitas por prisionero a PDF
- [ ] Integración de roles con Spatie (admin vs guardia)
- [ ] Diseño mejorado con Tailwind / Kometa UI Kit

---

## 👥 Equipo

| Integrante | Rama | Responsabilidad |
|------------|------|-----------------|
| Cristian | `cris` | Panel admin, reportes PDF/Excel, diseño |
| ... | ... | ... |

---

> Proyecto ADSO · Cárcel El Redentor · Laravel 13