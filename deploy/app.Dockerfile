# Stage 1: Build de la App Yii2
FROM yiisoftware/yii2-php:7.4-apache AS builder
WORKDIR /app
COPY . .
# Instalar dependencias y build de la aplicacion Angular
RUN composer install


# Stage 2: Serve App
FROM yiisoftware/yii2-php:7.4-apache
COPY --from=builder /app /app
COPY ./deploy/vendor/2amigos /app/vendor/2amigos
RUN chown -R www-data:www-data /app