# Imagen base
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Configurar el directorio de trabajo
WORKDIR /var/www

# Copiar los archivos necesarios al contenedor
COPY composer.json composer.lock /var/www/
COPY . /var/www

# Ajustar permisos
RUN chown -R www-data:www-data /var/www

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Crear carpetas necesarias y archivo SQLite
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache /var/www/database \
    && touch /var/www/database/database.sqlite \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database/database.sqlite

# Comando predeterminado
CMD ["php-fpm"]