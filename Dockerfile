FROM php:8.3-fpm

# Installer les extensions système nécessaires
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip libzip-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Dossier de travail
WORKDIR /var/www

# Copier les fichiers Laravel dans l’image
COPY . .

# Installer les dépendances Laravel
RUN composer install --optimize-autoloader --no-dev

# Donner les bonnes permissions
RUN chmod -R 755 storage bootstrap/cache

# Exposer le port pour Railway
EXPOSE 8000

# Commande de lancement pour Railway
CMD php -r "passthru('php artisan serve --host=0.0.0.0 --port='.(int)getenv('PORT'));"
