API de Gestión de Inventario — Prueba Técnica

Este proyecto consiste en una API RESTful robusta desarrollada con Laravel 11 para la administración eficiente de productos. La solución integra autenticación segura mediante JWT, un sistema CRUD completo y procesos de alto rendimiento diseñados para la importación y exportación masiva de datos (+100,000 registros).

##Características Principales

-Autenticación: Implementada con JSON Web Tokens (JWT).

-Gestión CRUD: Operaciones completas de productos con validaciones estrictas.

-Exportación Optimizada: Generación de archivos CSV mediante streaming para minimizar el consumo de memoria RAM.

-Importación Masiva: Procesamiento de archivos CSV con validaciones de integridad y carga por bloques (batch inserts).

-Auditoría: Registro detallado de errores de importación en logs del sistema.

-Escalabilidad: Arquitectura preparada para manejar volúmenes superiores a 100,000 registros.

###Requisitos del Sistema

Antes de iniciar la instalación, asegúrese de contar con:

PHP: 8.2 o superior.

Composer: Gestor de dependencias de PHP.

MySQL: Motor de base de datos.

Git: Para el control de versiones.

###Configuración de la Base de Datos
IMPORTANTE: No es necesario importar archivos .sql manuales. El proyecto utiliza Migraciones de Laravel para garantizar la integridad estructural de forma automática.

###Pasos de configuración:

##Crear la base de datos en su gestor MySQL:

SQL

CREATE DATABASE inventario;

Configurar el entorno: En el archivo .env, defina las credenciales de conexión:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventario
DB_USERNAME=su_usuario
DB_PASSWORD=su_contraseña

###Ejecutar Migraciones:

php artisan migrate

###Instalación Paso a Paso
**Siga este orden para poner en marcha el proyecto:**

#Clonar el repositorio:

git clone https://github.com/tu-usuario/inventario-api.git
cd inventario-api
Instalar dependencias:

-composer install

#Configurar variables de entorno:

-cp .env.example .env

Nota: Asegúrese de generar o definir una clave para JWT_SECRET en su archivo .env para habilitar la autenticación.

#Generar clave de aplicación:

-php artisan key:generate

#Iniciar el servidor local:

-php artisan serve

##Pruebas con Postman

Se ha incluido una colección de Postman en la raíz del proyecto: Inventario_API.postman_collection.json.

###NOTA CRÍTICA SOBRE AUTENTICACIÓN

Para consumir los endpoints protegidos, siga estos pasos estrictamente:

Generación de Token: Primero, ejecute la petición en el endpoint /api/login (Token_autenticacion) con credenciales válidas.

Uso del Token: Copie el token generado en la respuesta.

Configuración: En cada petición protegida (Create, Update, Delete, Import, Export), diríjase a la pestaña Authorization, seleccione el tipo Bearer Token y pegue el código obtenido.

Expiración: Los endpoints retornarán un error 401 Unauthorized si el token es inválido o ha expirado.

### se debe tener en cuanta que para hacer update y delete los id{} deben existir en la tabla sino se generaran errores
### tener en cuenta que el servidor de XAMPP debe estar activo, esto para ejecucion local.
