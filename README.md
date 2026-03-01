# ⚙️ API Backend - Portal de Empleados

**Descripción del Proyecto**
Este repositorio contiene el Backend (API REST) para un sistema integral de gestión de empleados. Desarrollado con **PHP 8** y **MySQL**, su propósito principal es gestionar de forma segura y eficiente la información del personal, proveyendo datos limpios a través de endpoints JSON. 

El sistema no solo maneja el clásico CRUD (Crear, Leer, Actualizar, Eliminar), sino que implementa lógica de negocios real, como la prevención de correos duplicados y el manejo de **Bajas Lógicas** (soft deletes) para preservar la integridad histórica de la base de datos. Además, incluye endpoints estadísticos diseñados específicamente para alimentar un Dashboard interactivo en el Frontend.

## Tecnologías Utilizadas

* **Lenguaje:** PHP 8.x 
* **Base de Datos:** MySQL 8.0
* **Conexión BD:** PDO (PHP Data Objects)
* **Entorno de BD:** Docker & Docker Compose
* **Arquitectura:** API RESTful (Respuestas JSON)

## Características Principales

1. **CRUD Completo:** Operaciones de Alta, Baja, Consulta y Modificación.
2. **Baja Lógica:** Los registros se ocultan (`estatus_activo = 0`) en lugar de borrarse con `DELETE`, manteniendo la integridad referencial.
3. **Manejo de Excepciones:** Captura de violaciones de integridad (Ej. Error 1062) para evitar duplicidad de correos electrónicos.
4. **Seguridad:** Prevención de Inyecciones SQL usando sentencias preparadas (`bindValue()`) y limpieza de inputs (`htmlspecialchars`).
5. **CORS Habilitado:** Cabeceras configuradas para permitir peticiones transversales desde aplicaciones Frontend (Angular, React, etc.).

## Endpoints Disponibles

| Endpoint | Método | Descripción |
| :--- | :---: | :--- |
| `/obtener_empleados.php` | `GET` | Devuelve la lista de todos los empleados activos. |
| `/obtener_empleado.php?id={id}` | `GET` | Devuelve los datos de un empleado específico. |
| `/agregar_empleado.php` | `POST` | Registra un nuevo empleado validando duplicados. |
| `/actualizar_empleado.php?id={id}`| `PUT` | Actualiza la información de un empleado existente. |
| `/eliminar_empleado.php?id={id}` | `DELETE`| Realiza la baja lógica (`estatus_activo = 0`). |
| `/obtener_catalogos.php` | `GET` | Devuelve listas de Departamentos y Puestos. |
| `/obtener_dashboard.php` | `GET` | Devuelve KPIs (totales, activos) y datos para gráficas. |

## 🐳 Configuración con Docker

Para levantar la base de datos rápidamente sin instalar MySQL directamente en tu sistema operativo, este proyecto incluye soporte para Docker.

**Archivo `docker-compose.yml` requerido:**
Si vas a clonar este proyecto, asegúrate de crear o tener este archivo en la raíz:

```yaml
version: '3.8'

services:
  db:
    image: mysql:8.0
    container_name: mysql_empleados
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: empleados_db
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```
## Desarrollado por Eric Valera

'''
## Desarrollado por Eric Valera