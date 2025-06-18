# Imagen base oficial de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libonig-dev \
    pkg-config \
    build-essential \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql intl xml mbstring \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar código fuente al contenedor
COPY . /var/www/html

# Copiar archivo .env directamente
#COPY .env /var/www/html/.env

# Cambiar directorio de trabajo
WORKDIR /var/www/html

# Habilitar mod_rewrite para Laravel
RUN a2enmod rewrite

# Configurar DocumentRoot a /public
RUN echo 'DocumentRoot /var/www/html/public' > /etc/apache2/conf-available/document-root.conf && \
    echo '<Directory /var/www/html/public>' >> /etc/apache2/conf-available/document-root.conf && \
    echo '    Options Indexes FollowSymLinks' >> /etc/apache2/conf-available/document-root.conf && \
    echo '    AllowOverride All' >> /etc/apache2/conf-available/document-root.conf && \
    echo '    Require all granted' >> /etc/apache2/conf-available/document-root.conf && \
    echo '    DirectoryIndex index.php' >> /etc/apache2/conf-available/document-root.conf && \
    echo '</Directory>' >> /etc/apache2/conf-available/document-root.conf && \
    a2enconf document-root && \
    a2dissite 000-default.conf && \
    echo '<VirtualHost *:80>' > /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '    ServerAdmin webmaster@localhost' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '    DocumentRoot /var/www/html/public' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '    <Directory /var/www/html/public>' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '        Options Indexes FollowSymLinks' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '        AllowOverride All' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '        Require all granted' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '    </Directory>' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '    ErrorLog ${APACHE_LOG_DIR}/error.log' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '    CustomLog ${APACHE_LOG_DIR}/access.log combined' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    echo '</VirtualHost>' >> /etc/apache2/sites-available/000-rinconcito.conf && \
    a2ensite 000-rinconcito.conf

# Instalar dependencias PHP
RUN composer install --no-dev --optimize-autoloader

# Generar clave de aplicación
#RUN php artisan key:generate

# Limpiar y cachear config, rutas y vistas
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Establecer permisos correctos
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto 80
EXPOSE 80

# Mostrar log de Laravel si existe, luego iniciar Apache
CMD if [ -f storage/logs/laravel.log ]; then echo '--- CONTENIDO DE LARAVEL.LOG ---' && cat storage/logs/laravel.log; else echo 'No hay archivo storage/logs/laravel.log'; fi && apache2-foreground
