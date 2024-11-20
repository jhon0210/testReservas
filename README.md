
## **Estructura y Diseño del Sistema**

El sistema se organiza siguiendo la arquitectura **Model-View-Controller (MVC)**, aunque se enfoca principalmente en la capa de modelo y repositorio, se detalla la estructura principal del proyecto:

### **Directorio Principal**
- `app/`: Contiene la lógica principal de la aplicación.
  - `Models/`: Contiene los modelos que representan las tablas en la base de datos (`Resource`, `Reservation`).
  - `Repositories/`: Implementa la lógica de acceso a datos y encapsula consultas complejas a la base de datos.
  - `Factories/`: Implementa la creación de instancias de objetos complejos, como Resource y Reservation.
  - `Http/Controllers/`: Contiene los controladores responsables de manejar las solicitudes HTTP y delegar la      lógica al servicio correspondiente.
- `database/`: Contiene las migraciones, fábricas y seeders para configurar y poblar la base de datos.
  - `factories/`: Genera datos de prueba para modelos.
  - `migrations/`: Scripts que definen la estructura de la base de datos.
- `routes/`: Contiene el archivo api.php donde estan estructuradas las rutas de la api.
- `tests/`: Contiene pruebas unitarias y de integración para validar el correcto funcionamiento del sistema.
  - `Unit/`: Pruebas individuales para funciones específicas.
  - `Feature/`: Pruebas que verifican el comportamiento de la API en conjunto.

### **Diseño de la API**
- Endpoints:
  - `POST /api/alta`: Crear un recurso.
  - `GET /api/resources`: Lista los recursos disponibles.
  - `POST /api/reservations`: Crea una nueva reserva para un recurso específico.
  - `GET /api/resources/{id}/availability/{reserved_at}/{duration}`: Verifica la disponibilidad de un recurso para un horario específico utilizando como parametros en la ruta id_resource, reserved_at y duration.
  - `DELETE /api/reservations/{id}`: Cancela una reserva utilizando el id de la tabla reservations.
- Base de datos:
  - **Tabla `resources`**:
    - Campos principales: `nombre`, `descripcion`, `capacidad`.
  - **Tabla `reservations`**:
    - Relación con `resources` (clave foránea `resource_id`).
    - Campos principales: `reserved_at`, `duration`, `status`.

## **Decisiones de Diseño**

### **Patrón MVC**
El patrón MVC se eligió porque:
- Es un estándar en Laravel y permite una clara separación de responsabilidades.
- Facilita la escalabilidad al organizar el código de manera coherente.

### **Uso del Patrón Repositorio**
El patrón repositorio encapsula la lógica de acceso a datos, proporcionando:
- **Modularidad**: Las consultas complejas se abstraen, lo que facilita el cambio del motor de base de datos si es necesario.
- **Reutilización**: La misma lógica de datos se puede usar en diferentes partes de la aplicación.

### **Uso del atrón de Diseño Factory**
Se implemento para generar instancias de modelos en las pruebas, facilitando las mismas permitiendo crear registros en la base de datos sin tener que definir explícitamente todos los valores cada vez que se cree un modelo, lo que acelera las pruebas.

### **Tecnologias Uitlizadas**
- `SQLite`: Se utilizo para agilidad en temas de confituracion y facilidad a la hora de realizar pruebas
- `Docker`: Se utilizo para facilitar la instalacion del proyecto en equipos locales para su revision

### **Pasos de Instalacion**

- Ubicarse en la carpeta donde quiere iniciar el proyecto en la consola
- Ejecutar el comando git clone https://github.com/jhon0210/testReservas.git
- Utilizar la consola de su preferencia y ubicarse en la carpeta del proycto que acaba de extraer del repositorio
- Tener instalado Docker o en su defecto instalar Docker Desktop
- Ejecutar el comando docker compose up --build
- Probar las rutas con la herramienta de su preferencia (Postman o la extension de visual studio code "ThunderClient")
- para ejecutar las pruebas, pueden abrir otra ventana en la consola, ubicarsen en la carpeta del proyecto y ejecutar el comando php artisan test.

`Nota`: en la tabla resources ya hay 3 recursos creados, con los cuales se pueden empezar a realizar las pruebas de los endpoints para listarlos y demas, o si desean crear un recurso nuevo se puede realizar sin ningun problema.



