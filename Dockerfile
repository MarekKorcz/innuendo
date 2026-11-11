FROM php:7.3-apache

# Przełączenie repozytoriów Debiana na archiwum, ponieważ Debian Bullseye nie ma już wsparcia
RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i 's/security.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    sed -i '/bullseye-updates/d' /etc/apt/sources.list

# Instalacja zależności systemowych i rozszerzeń PHP wymaganych przez Laravel 5.7
RUN apt-get update && apt-get install -y --allow-downgrades --allow-remove-essential --allow-change-held-packages \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl

# Czyszczenie cache
RUN apt-get clean && rm -rf /var/lib/lists/*

# Instalacja rozszerzeń PHP (Usunąłem libonig-dev, mbstring jest wbudowane lub nie wymaga osobnego dev w tym obrazie)
RUN docker-php-ext-install pdo_mysql exif pcntl bcmath gd

# Włączenie mod_rewrite dla Apache
RUN a2enmod rewrite headers

# Zmiana DocumentRoot na katalog /public Laravela
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Instalacja Composera (wersja 2.2, stabilna dla PHP 7.3)
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Nadanie uprawnień po uruchomieniu kontenera
CMD chown -R www-data:www-data storage bootstrap/cache && apache2-foreground
