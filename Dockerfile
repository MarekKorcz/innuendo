FROM php:8.5-apache

# Instalacja zależności systemowych i rozszerzeń PHP wymaganych przez nowoczesnego Laravela
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

# Czyszczenie cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalacja rozszerzeń PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Włączenie mod_rewrite oraz mod_headers dla Apache
RUN a2enmod rewrite headers

# Zmiana DocumentRoot na katalog /public Laravela
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalacja najnowszego Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Nadanie uprawnień po uruchomieniu kontenera
CMD chown -R www-data:www-data storage bootstrap/cache && apache2-foreground
