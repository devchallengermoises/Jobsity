FROM php:8.2-cli-alpine

WORKDIR /var/www

# Instalar dependencias del sistema y extensiones de PHP
RUN apk add --no-cache linux-headers \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && docker-php-ext-install sockets pdo_mysql \
    && apk del .build-deps

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# Copiar archivos del proyecto
COPY . /var/www

# Instalar dependencias PHP y autoload
RUN composer install && composer dump-autoload

# Copiar archivo de entorno de ejemplo
RUN cp -R .env.sample .env

# Comando por defecto (se sobreescribe en docker-compose)
CMD ["php", "-S", "0.0.0.0:80", "-t", "public"] 