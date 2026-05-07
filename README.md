<p align="center">
  <strong>Plataforma integral para la gestión de Formación en Centros de Trabajo (FCT)</strong>
</p>

---

## Descripción

**ConectaFP** es una aplicación web diseñada para facilitar la gestión completa de las prácticas en empresas para estudiantes de Formación Profesional. La plataforma conecta tres actores principales:

- **Alumnos**: Buscan ofertas (proximamente lista de interés de ofertas)
- **Profesores**: Gestionan y supervisan las tutorías de sus alumnos
- **Empresas**: Publican ofertas y seleccionan candidatos

## Características

### Para Alumnos
- Explorar ofertas de prácticas disponibles
- Seguimiento del estado de solicitudes
- Acceso a información de tutoría

### Para Profesores
- Gestión de alumnos asignados
- Supervisión de tutorías
- Enviar solicitudes de los alumnos asignados a las empresas
- Seguimiento del progreso de prácticas

### Para Empresas
- Publicación de ofertas de prácticas
- Revisión de solicitudes recibidas
- Selección de candidatos

### Características Generales
- Sistema de autenticación seguro
- Sistema de notificaciones
- Interfaz moderna
- Sistema de búsqueda y filtrado

## Tecnologías

### Backend
- **Laravel 12** - Framework PHP
- **PHP 8.2+**
- **MySQL 8.0** - Base de datos
- **Laravel Sanctum** - Autenticación API

### Frontend
- **React 19** - Librería UI
- **Tailwind CSS 4** - Framework CSS
- **Vite** - Build tool
- **PostCSS** - Procesamiento CSS

### DevOps
- **Docker** - Contenedores
- **Docker Compose** - Orquestación de contenedores
- **Nginx** - Servidor web para logs
- **Apache** - Servidor de aplicaciones

## Instalación

### Prerrequisitos
- Docker y Docker Compose instalados
- Git

### Instalación con Docker (Recomendado)

1. **Clonar el repositorio**
```bash
git clone https://github.com/tu-usuario/conectaFP.git
cd conectaFP
```
2. **Levantar servicios en modo desarollador**
```bash
# 1. Levantar contenedores de Docker
docker compose up -d

# 2. Levantar servicio de Node.js para compilar componentes React
docker compose exec app npm run dev --host
```
3. **Instalar dependencias**
```bash
docker compose exec app composer setup
```
4. **Configurar variables de entorno**
```bash
cp src/.env.example src/.env
# Editar src/.env con tus configuraciones
```
5. **Ejecutar migraciones**
```bash
docker compose exec app php artisan migrate
```

## Estructura del Proyecto

```bash
conectaFP/
├── docker/                 # Configuración Docker
│   ├── apache/            # Dockerfile Apache
│   └── nginx/             # Configuración Nginx
├── src/                   # Código fuente Laravel
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/    # Controladores
│   │   ├── Models/             # Modelos Eloquent
│   │   │   ├── Alumno.php
│   │   │   ├── Empresa.php
│   │   │   ├── Oferta.php
│   │   │   ├── Profesor.php
│   │   │   ├── Solicitud.php
│   │   │   └── ...
│   │   └── Notifications/      # Notificaciones
│   ├── database/
│   │   ├── factories/          # Factories para testing
│   │   ├── migrations/         # Migraciones de BD
│   │   └── seeders/            # Seeders
│   ├── resources/
│   │   ├── css/               # Estilos
│   │   ├── js/                # Componentes React
│   │   └── views/             # Vistas Blade
│   ├── routes/
│   │   ├── api.php            # Rutas API
│   │   ├── web.php            # Rutas web
│   │   └── auth.php           # Rutas autenticación
│   └── tests/                 # Tests
├── docker-compose.yml     # Configuración servicios
└── README.md
```

## Tests unitarios

1. **Ejecutar todos los tests**
```bash
docker compose exec app php artisan test tests/Unit/
```
2. **Ejecutar un test**
```bash
docker compose exec app php artisan test tests/Unit/TutoriaApiRest.php
```

## Rutas Principales

### Usuario
- /login - Inicio de sesión.
- /register - Registro de usuarios.
- /feed - Feed principal (Proximamente).

### Alumno
- /ofertas - Listado de ofertas disponibles.
- /mis-solicitudes - Las solicitudes de las ofertas asignadas por el profesor.
- /mi-tutoria - Ver tutorías con el profesor asignado.

### Profesor
- /ofertas - Listado de ofertas disponibles y herramientas para solicitar una oferta a los alumnos asignados.
- /mis-solicitudes - Ver las solicitudes enviadas a la empresa.
- /mi-tutoria - Ver y crear tutorías a alumnos ya asignados en las empresas.

### Empresa
- /ofertas - Ver y crear ofertas de prácticas.
- /solicitudes - Ver solicitudes enviadas por el profesor y modificar el estado de la solicitud (Pendiente, Revisión, Aceptada, Rechazada).

## Futuros desarrollos
- Aplicación móvil.
- Sistema de mensajería y comunicación en la sección de tutoría (chat).
- Autenticación externa (Google).
- Feed para que las empresas, alumnos y profesores puedan publicar publicaciones, actividades desarrolladas en las empresas o valoraciones de las empresas y alumnos.
- Gestión de perfil (seguidores, estudios…).

## Créditos

**Desarrollado por OllisDev**
  
