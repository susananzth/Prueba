# Módulo de Gestión de Trabajadores - Prueba Técnica

Este es un sistema básico de administración de trabajadores desarrollado como parte de una evaluación técnica. El módulo permite gestionar el personal de una empresa, asignarlos a proyectos específicos y llevar un registro de sus cargos y estados de actividad.

## 🚀 Stack Tecnológico

- **Backend**: Laravel 12.x (PHP 8.4)
- **Base de Datos**: MySQL 8.0
- **Frontend**: Bootstrap 5.3, jQuery 3.7, FontAwesome 6, SweetAlert2
- **Arquitectura**: API RESTful con consumo vía AJAX
- **Contenedores**: Docker & Docker Compose

## 📋 Requisitos Previos

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado y en ejecución.
- [Postman](https://www.postman.com/downloads/) (opcional, para pruebas de API).

## 🛠️ Instalación y Despliegue

Sigue estos pasos para levantar el proyecto en tu entorno local:

### 1. Clonar el repositorio
```bash
git clone https://github.com/susananzth/Prueba.git
cd Prueba
```

### 2. Levantar los contenedores
Este comando construirá las imágenes y levantará los servicios de App (PHP-FPM), Web (Nginx) y DB (MySQL).
```bash
docker compose up -d --build
```

### 3. Configurar la Base de Datos
Una vez que los contenedores estén activos, ejecuta las migraciones y el seeder para poblar la base de datos con información de prueba:
```bash
docker compose exec app php artisan migrate:fresh --seed --seeder=WorkersSeeder
```

## 🖥️ Uso de la Aplicación

### Interfaz Web
Puedes acceder a la interfaz de gestión desde tu navegador en:
👉 **[http://localhost:8001](http://localhost:8001)**

**Características:**
- Listado dinámico de trabajadores.
- Búsqueda en tiempo real por nombre, cargo o email.
- Filtro por proyectos activos.
- Registro y edición mediante modales de Bootstrap.
- Vista de detalles con historial de contratos.
- Desactivación lógica de trabajadores.

### Pruebas con Postman
Se ha incluido un archivo de colección para facilitar las pruebas de los endpoints.

1. Abre **Postman**.
2. Haz clic en **Import**.
3. Selecciona el archivo **`script.postman_collection.json`** ubicado en la raíz del proyecto.
4. La colección incluye una variable `base_url` configurada como `http://localhost:8001/api`.

## 🛰️ Endpoints de la API

| Método | Endpoint | Descripción |
| :--- | :--- | :--- |
| `GET` | `/api/employees` | Listar todos los trabajadores (soporta `search` y `project_id`). |
| `POST` | `/api/employees` | Registrar un nuevo trabajador. |
| `GET` | `/api/employees/{id}` | Ver detalle de un trabajador y sus contratos. |
| `PUT` | `/api/employees/{id}` | Actualizar datos del trabajador. |
| `DELETE` | `/api/employees/{id}` | Desactivar (soft delete lógico) un trabajador. |
| `GET` | `/api/projects` | Listar proyectos activos para selectores. |

## ⚙️ Consideraciones del Desarrollo

- **Frontend**: Se priorizó una estética limpia y moderna utilizando una paleta basada en HSL y efectos de transparencia (Glassmorphism).
- **Validaciones**: Se implementaron validaciones tanto en el Frontend (vía jQuery) como en el Backend (Laravel Form Requests/Validators), devolviendo errores en formato JSON con estados HTTP apropiados (422 Unprocessable Entity).
- **Persistencia**: El sistema maneja una relación N:M entre empleados y proyectos a través de contratos, permitiendo rastrear el historial laboral.
- **Docker**: La configuración incluye un `docker-entrypoint.sh` robusto que espera a que la base de datos esté lista antes de iniciar los servicios.

---
Desarrollado por **susananzth**.
