# Sistema de Gestión de Usuarios y Cursos - CakePHP 2.10

Sistema web desarrollado con CakePHP 2.10.24 y Bootstrap 5 para la gestión de usuarios y cursos con dos perfiles (Admin y Usuario).

## 🚀 Demo en Vivo

**URL:** [Próximamente - En proceso de deploy]

**Usuarios de prueba:**

- **Admin:** admin@test.com / admin123
- **Usuario:** usuario1@test.com / user123

## 📋 Características Principales

### Autenticación y Seguridad

- Login con email y contraseña
- Encriptación de contraseñas con Blowfish (bcrypt)
- Dos perfiles de usuario: Admin y Usuario
- Protección de rutas según rol
- Historial de cambios (Audit Log)

### Perfil Administrador

- **Dashboard con estadísticas**
  - Métricas en tiempo real
  - Gráficos de ocupación de cursos
  - Distribución de usuarios
- **Gestión de Usuarios**
  - CRUD completo
  - Activar/desactivar (con AJAX)
  - Cambiar contraseñas
  - Búsqueda en tiempo real
  - Filtros por rol y estado
  - Exportar a CSV
- **Gestión de Cursos**
  - CRUD completo
  - Control de cupos
  - Agregar/quitar usuarios (con dropdown de checkboxes múltiples)
  - Búsqueda en tiempo real
  - Filtros por estado
  - Exportar a CSV
  - Exportar lista de estudiantes por curso
- **Historial de Cambios**
  - Registro de todas las acciones
  - Filtros en tiempo real
  - IP y fecha de cada acción

### Perfil Usuario

- Ver mis cursos inscritos
- Ver información completa de cada curso
- Ver compañeros de curso
- Buscar compañeros

### Funcionalidades AJAX

- Activar/desactivar usuarios sin recargar
- Activar/desactivar cursos sin recargar
- Búsqueda en tiempo real (usuarios, cursos, historial)
- Filtros en tiempo real
- Notificaciones visuales

## 🛠️ Tecnologías Utilizadas

- **Backend:** CakePHP 2.10.24
- **Frontend:** Bootstrap 5.1.3
- **JavaScript:** jQuery 3.6.0
- **Gráficos:** Chart.js 3.9.1
- **Base de datos:** MySQL
- **Encriptación:** Blowfish (PASSWORD_BCRYPT)

## 📦 Instalación Local

### Requisitos Previos

- PHP 5.6 o superior
- MySQL 5.6 o superior
- Apache con mod_rewrite habilitado
- Extensión PHP: mbstring, intl, pdo_mysql

### Pasos de Instalación

1. **Clonar el repositorio**

```bash
git clone https://github.com/tomasbeckmann/sistema-gestion-usuarios-cursos-cakephp2
cd PruebaTecnicaEclass
```

2. **Importar la base de datos**

```bash
mysql -u root -p < database.sql
```

O desde phpMyAdmin:

- Crear base de datos: `mantenedor_usuarios`
- Importar el archivo `database.sql`

3. **Configurar la base de datos**

Editar `app/Config/database.php`:

```php
public $default = array(
    'datasource' => 'Database/Mysql',
    'persistent' => false,
    'host' => 'localhost',
    'login' => 'root',
    'password' => '',
    'database' => 'mantenedor_usuarios',
    'prefix' => '',
    'encoding' => 'utf8',
);
```

4. **Configurar Security Salt y Cipher Seed**

Editar `app/Config/core.php` y cambiar estos valores por unos únicos:

```php
Configure::write('Security.salt', 'TU_VALOR_UNICO_AQUI');
Configure::write('Security.cipherSeed', 'TU_NUMERO_UNICO_AQUI');
```

5. **Configurar permisos (Linux/Mac)**

```bash
chmod -R 777 app/tmp
```

6. **Configurar Virtual Host (Opcional pero recomendado)**

Agregar en `httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName pruebatecnica.local
    DocumentRoot "C:/xampp/htdocs/PruebaTecnicaEclass/app/webroot"
    <Directory "C:/xampp/htdocs/PruebaTecnicaEclass/app/webroot">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Agregar en archivo `hosts`:

```
127.0.0.1 pruebatecnica.local
```

7. **Acceder a la aplicación**

- Con VirtualHost: `http://pruebatecnica.local`
- Sin VirtualHost: `http://localhost/PruebaTecnicaEclass`

## 👤 Usuarios de Prueba

La base de datos incluye usuarios precargados:

| Email             | Password | Rol           |
| ----------------- | -------- | ------------- |
| admin@test.com    | admin123 | Administrador |
| usuario1@test.com | user123  | Usuario       |
| usuario2@test.com | user123  | Usuario       |

## 📊 Estructura de Base de Datos

- **users** - Usuarios del sistema
- **courses** - Cursos disponibles
- **courses_users** - Relación muchos a muchos entre usuarios y cursos
- **audit_logs** - Historial de cambios del sistema

## 🔒 Seguridad Implementada

- Encriptación de contraseñas con Blowfish (bcrypt)
- Protección CSRF con tokens automáticos
- Protección contra SQL Injection (PDO prepared statements)
- Protección XSS con helper h()
- Validaciones server-side
- Verificación de roles en cada acción sensible
- Prevención de auto-eliminación del admin
- Registro de IPs en el historial

## 📝 Validaciones Implementadas

- Email único y formato válido
- Contraseñas requeridas y encriptación automática
- Fecha de fin mayor a fecha de inicio
- Control de cupos máximos en cursos
- Prevención de duplicados en asignaciones
- Verificación de permisos por rol

## 🎨 Convenciones CakePHP

- Nomenclatura estándar (Modelos singular, Controllers plural)
- Routing automático
- Helpers nativos (Form, Html, Paginator)
- AuthComponent para autenticación
- Componentes personalizados (AuditLog)
- Estructura MVC completa

## 📱 URLs del Sistema

- `/` - Login
- `/admin/dashboard` - Dashboard principal (Admin)
- `/admin/users` - Gestión de usuarios (Admin)
- `/admin/courses` - Gestión de cursos (Admin)
- `/admin/logs` - Historial de cambios (Admin)
- `/users/index` - Mis cursos (Usuario)

## 🤝 Autor

[Tu Nombre]

## 📄 Licencia

Este proyecto fue desarrollado como prueba técnica.
