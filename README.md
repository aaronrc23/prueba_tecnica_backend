# Backend Legacy Laravel 8

Este proyecto representa una API legacy con problemas intencionales. El objetivo del candidato es migrarlo, optimizarlo y refactorizarlo.

## Stack actual legacy

- Laravel 8
- PHP 7.4 / 8.0
- MySQL
- Sin Swagger
- Sin Telescope
- Sin auditoría formal
- Sin pruebas reales

## Instalación inicial

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## URL base

```txt
http://127.0.0.1:8000/api
```

## Credenciales de prueba

```txt
email: admin@legacy.test
password: password
```

## Endpoints legacy

```txt
POST /api/login
GET  /api/products
POST /api/products
GET  /api/products/{id}
PUT  /api/products/{id}
DELETE /api/products/{id}
GET  /api/categories
POST /api/categories
PUT  /api/categories/{id}
DELETE /api/categories/{id}
GET  /api/products/{id}/stock-movements
POST /api/products/{id}/stock-movements
GET  /api/dashboard
GET  /api/health
```



## Error en las dependencias desactualizadas


```txt
fruitcake/laravel-cors": "^2.0, 
"php": "^7.4|^8.0",

```
1.-Eliminacion la dependencia  "fruitcake/laravel-cors" por que esta desactualiza ya que laravel 11  tiene un comando para manejar las cors .

2.-Actualizacion la version de php a una version mas reciente como la ^8.2 el cual es compatible con laravel  11 
3.-Agrege las carpetas para el manejo de la cache en las carpetas bootstrap ,framework.
4.-Creacion de la migracion de  tabla cache 

```txt
 public function up(): void
    {
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
```
5.-Instalacion de Sanctum 
```txt
 php artisan install:api
```
6-Instalacion e implementacion del archivo cors en la carpeta config 

```txt
 php artisan config:publish cors
```
## Estructura del proyecto en base al modelo MVC
1.-Implementacion de optimizaciones
 Se optimizó la estructura de la API mediante el uso de **Form Requests** para las validaciones en los controlladores de login ,producto ,Categoria.

2.-Se implementaron **API Resources** para unificar el formato de las respuestas JSON.

3-Se mejoró el proceso de autenticación integrando **Laravel Sanctum** para el manejo seguro de tokens.





