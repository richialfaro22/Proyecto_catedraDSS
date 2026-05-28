Aplicacion Web sobre Control de citas para clinica Dermatologica
Desarrollado con: 
Parte del backend: Laravel
Parte del frontend: Vue.js y Tailwind CSS
Base de datos: MySQL
API RESTful
Mas detalle sobre las tecnologías que se utilizaron en el proyecto 
Backend
•	PHP 8 
•	Laravel 
•	Laravel Sanctum 
•	MySQL 
Frontend
•	Vue.js 
•	Vite 
•	Tailwind CSS 
•	Axios

Pasos para la instalación de este proyecto 
1.	Clonar el repositorio 
2.	Luego ir a la carpeta del proyecto con el comando cd (cd Proyecto_catedraDSS)
3.	Instalar dependencias con el comando composer install
4.	Copiar los datos del archivo .env.example para luego crear el archivo .env 
5.	Configurar variables de entorno 
6.	Generar una clave con el comando: php artisan key:generate
7.	Ejecutar las migraciones con el comando: php artisan migrate
8.	Correr el servidor con el comando: php artisan serve 
Configuracion del lado del Frontend 
1.	Entrar a la carpeta del frontend con el comando cd (cd frontend)
2.	Instalar dependencias con el comando: npm install
3.	Luego para correr el entorno de desarrollo se utiliza el comando: npm run dev la url al que corre el frontend es por defecto al http://localhost:5173

Estructura del proyecto por módulos 
Dashboard
Pacientes
Doctores
Citas
Historial Clínico
Prescripciones
Tratamientos

Endpoints principales 
Método   EndPoint
POST      /login
POST      /register
POST      /logout


| Método | Endpoint       |
| ------ | -------------- |
| GET    | /patients      |
| POST   | /patients      |
| GET    | /patients/{id} |
| PUT    | /patients/{id} |
| DELETE | /patients/{id} |


| Método | Endpoint      |
| ------ | ------------- |
| GET    | /doctors      |
| POST   | /doctors      |
| PUT    | /doctors/{id} |
| DELETE | /doctors/{id} |


| Método | Endpoint           |
| ------ | ------------------ |
| GET    | /appointments      |
| POST   | /appointments      |
| PUT    | /appointments/{id} |
| DELETE | /appointments/{id} |


| Método | Endpoint              |
| ------ | --------------------- |
| GET    | /medical-records      |
| POST   | /medical-records      |
| PUT    | /medical-records/{id} |
| DELETE | /medical-records/{id} |


| Método | Endpoint            |
| ------ | ------------------- |
| GET    | /prescriptions      |
| POST   | /prescriptions      |
| PUT    | /prescriptions/{id} |
| DELETE | /prescriptions/{id} |


| Método | Endpoint         |
| ------ | ---------------- |
| GET    | /treatments      |
| POST   | /treatments      |
| PUT    | /treatments/{id} |
| DELETE | /treatments/{id} |

