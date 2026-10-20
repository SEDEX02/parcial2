INTEGRANTES: Juan Triana, Santiago Gambin, Néstor Gonzales y Duban Salas  
  
  Requisitos

- PHP 8.0 o superior con extensiones PDO y PDO MySQL.
- MySQL o MariaDB.
- XAMPP o Laragon.
- Navegador web actualizado.

Instalación y configuración en XAMPP

1. Descomprima el ZIP.
2. Copie la carpeta `cine` a `C:\xampp\htdocs\`.
3. Abra XAMPP e inicie Apache y MySQL.
4. Entre en `http://localhost/phpmyadmin`.
5. Importe `cine/database/cine.sql`. El script crea y selecciona la base `cine`, crea las tablas y registra el administrador inicial.
6. Revise `cine/config/Database.php` y `cine/modelo/conectar.php`. La configuración incluida usa host local, usuario `root` y contraseña vacía, configuración común de XAMPP. Si su instalación usa otras credenciales, ajuste ambos archivos para que coincidan.
7. Abra `http://localhost/cine/vista/login.php`.

Credenciales iniciales

- Usuario: `admin`
- Contraseña: `admin123`

El SQL guarda la contraseña como hash compatible con `password_verify()`, no como texto plano.

Estructura principal

- `cine/index.php`: entrada principal, sesión, rutas y validación de solicitudes POST.
- `cine/config/Database.php`: conexión PDO utilizada por el módulo de funciones.
- `cine/modelo/`: conexión y repositorio de autenticación.
- `cine/models/`: modelos de administradores y funciones.
- `cine/controlador/`: autenticación y controlador de funciones.
- `cine/vista/`: login, menú y vistas de funciones.
- `cine/views/`: vistas y layout del módulo de administradores.
- `cine/database/cine.sql`: base de datos, tablas, relaciones, restricciones y administrador inicial.
- `cine/public/css/style.css`: estilos.
- `modulo sofia pre jardin/`: archivos de modelos, controladores y vistas de salas y películas entregados en el repositorio.

Módulo de funciones

1. Inicie sesión.
2. Asegúrese de que existan salas y películas en la base de datos.
3. Abra **Funciones** desde el menú.
4. Seleccione **Registrar función**, ingrese un código alfanumérico único, seleccione película y sala, y elija fecha y hora.
5. Desde el listado puede consultar por código o eliminar una función con confirmación.

El módulo valida en el servidor la existencia de la sala y la película, el código único y el formato de fecha/hora. También evita programar dos funciones en la misma sala a la misma fecha y hora.

Arquitectura

El proyecto separa modelos (acceso a datos mediante PDO y consultas preparadas), controladores (validación y flujo de acciones) y vistas (formularios y resultados). El módulo de funciones utiliza la conexión de `cine/config/Database.php`.

Verificación antes de entregar

Importe el SQL en una base vacía y pruebe el login, la creación/listado/búsqueda/eliminación de funciones, y los módulos de administradores, salas y películas. Los archivos de salas y películas se conservan tal como venían en el ZIP recibido; su integración completa con el enrutador principal debe probarse en XAMPP antes de considerar que todo el portal está validado.
