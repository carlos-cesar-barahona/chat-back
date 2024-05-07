#descarga la imagen de laravel
FROM bitnami/laravel


# Instala sudo y nano
RUN apt-get update && apt-get install -y sudo nano


#COPIAR EL CODIGO DE MI PROYECTO LOCAL A LA IMAGEN DE DOCKER
COPY . /app

#MAPEAR EL PUERTO DE MI CUMPUTADORA
EXPOSE 8000

#COMANDO PARA EJECUTAR EL SERVIDOR DE LARAVEL
CMD ["php","artisan","serve","--host=0.0.0.0","--port=8000"]


#CORRER COMANDOS EN LA TERMINAL
	# docker build -t bitnami/laravel .
	
#CUANDO YA SE CONSTRUYO LA IMAGEN
	# docker run --name container-laravel -v MY_PC_ROUTE:/app -p 8000:8000 --network mi-red 
	#--env ALLOW_EMPTY_PASSWORD=yes \
	#--env MARIADB_USER=root \
	#--env MARIADB_DATABASE=punto_venta \
	#-d bitnami/laravel
