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

# Instalar Node.js 20 (compatible con Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Copiar código fuente al contenedor
COPY . /var/www/html

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

# Instalar dependencias de Node.js y compilar assets
RUN npm install && npm run build

# Crear enlace simbólico al storage
RUN php artisan storage:link || true

# Cachear config, rutas y vistas (evita problemas en prod y mejora rendimiento)
RUN php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Establecer permisos adecuados
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache && \
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto HTTP
EXPOSE 80

# Mostrar logs (si existen) y ejecutar Apache
CMD if [ -f storage/logs/laravel.log ]; then echo '--- CONTENIDO DE LARAVEL.LOG ---' && cat storage/logs/laravel.log; else echo 'No hay archivo storage/logs/laravel.log'; fi && apache2-foreground
