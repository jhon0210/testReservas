# Proyecto: Sistema de Reservas de Recursos

Este proyecto es una API desarrollada en Laravel para gestionar la disponibilidad y reservas de recursos. La API utiliza SQLite como base de datos y sigue principios de diseño orientados a la modularidad y la simplicidad para facilitar su mantenimiento y escalabilidad.

---

## **Estructura y Diseño del Sistema**

El sistema se organiza siguiendo la arquitectura **Model-View-Controller (MVC)**, adaptada a las buenas prácticas de Laravel. A continuación, se detalla la estructura principal del proyecto:

### **1. Directorio Principal**
- `app/`: Contiene la lógica principal de la aplicación.
  - `Models/`: Contiene los modelos que representan las tablas en la base de datos (`Resource`, `Reservation`).
  - `Repositories/`: Implementa la lógica de acceso a datos y encapsula consultas complejas a la base de datos.
  - `Http/Controllers/`: Contiene los controladores responsables de manejar las solicitudes HTTP y delegar la lógica al servicio correspondiente.
- `database/`: Contiene las migraciones, fábricas y seeders para configurar y poblar la base de datos.
  - `factories/`: Genera datos de prueba para modelos.
  - `migrations/`: Scripts que definen la estructura de la base de datos.
- `tests/`: Contiene pruebas unitarias y de integración para validar el correcto funcionamiento del sistema.
  - `Unit/`: Pruebas individuales para funciones específicas.
  - `Feature/`: Pruebas que verifican el comportamiento de la API en conjunto.

### **2. Diseño de la API**
- Endpoints:
  - `GET /api/resources`: Lista los recursos disponibles.
  - `POST /api/reservations`: Crea una nueva reserva para un recurso específico.
  - `GET /api/resources/{id}/availability`: Verifica la disponibilidad de un recurso para un horario específico.
- Base de datos:
  - **Tabla `resources`**:
    - Campos principales: `name`, `description`, `capacity`.
  - **Tabla `reservations`**:
    - Relación con `resources` (clave foránea `resource_id`).
    - Campos principales: `reserved_at`, `duration`, `status`.

### **3. Patrón de Diseño Repositorio**
El acceso a datos se abstrae mediante el uso de repositorios (`ResourceRepository`, `ReservationRepository`) para desacoplar la lógica de consultas SQL del resto de la aplicación. Esto facilita el mantenimiento y pruebas.

---

## **Decisiones de Diseño**

### **1. Patrón MVC**
El patrón MVC se eligió porque:
- Es un estándar en Laravel y permite una clara separación de responsabilidades.
- Facilita la escalabilidad al organizar el código de manera coherente.

### **2. Uso del Patrón Repositorio**
El patrón repositorio encapsula la lógica de acceso a datos, proporcionando:
- **Modularidad**: Las consultas complejas se abstraen, lo que facilita el cambio del motor de base de datos si es necesario.
- **Reutilización**: La misma lógica de datos se puede usar en diferentes partes de la aplicación.

### **3. Base de Datos SQLite**
SQLite fue elegida para este proyecto porque:
- Es ligera y no requiere configuración adicional, ideal para entornos de desarrollo o pruebas rápidas.
- Permite portar el proyecto fácilmente a otras bases de datos más robustas en entornos de producción.

### **4. Pruebas Unitarias e Integrales**
Se integraron pruebas unitarias para garantizar:
- **Fiabilidad del código**: Cada componente funciona según lo esperado.
- **Prevención de regresiones**: Cambios en el código no rompen funcionalidades existentes.
  
### **5. Uso de Fábricas y Seeders**
Se utilizaron fábricas para generar datos de prueba, lo que:
- Acelera la configuración del entorno de desarrollo.
- Asegura un conjunto coherente de datos para las pruebas.


