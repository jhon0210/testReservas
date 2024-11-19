FROM php:8.2-cli

WORKDIR /var/www

COPY . .

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database/database.sqlite

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
