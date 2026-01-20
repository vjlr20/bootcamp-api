# Comandos de uso de Laravel

1. Generar proyecto por primera vez

    ```bash
	composer create-project laravel/laravel bootcamp-api
	```

2. Ejecutar servidor

    ```bash
	php artisan serve
	```
3. Accedemos a la URL que nos comparte el servidor http://127.0.0.1:8000 (http://localhost:8000)

4. Comando para ver las rutas

	```bash
	php artisan route:list
	```

5. Generar un controlador

	```bash
	php artisan make:controller HomeController
	```

6. Generamos modelo con migración
	
	```bash
	php artisan make:model Category --migration
	```

7. Ejecutamos las migraciones
	
	```bash
	php artisan migrate
	```

8. Revertimos las ultimas migraciones
	
	```bash
	php artisan migrate:rollback
	```

9. Generar un controlador con funciones predefinidas

	```bash
	php artisan make:controller CategoryController --resource
	```

10. Habilitar el trabajo para APIs e instalar la libreria de Autenticación

	```bash
	php artisan install:api --passport
	```

11. Generar un modelo con su migración y su controlador

	```bash
	php artisan make:model Category -mcr
	```
