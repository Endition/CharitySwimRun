FROM php:8.4-apache

ENV DB_SERVER=mysql
ENV DB_BENUTZER=my_db_user
ENV DB_PASSWORT=my_db_user_password

# Install system dependencies, PHP extensions, and tools in one layer to keep image size minimal.
# dos2unix converts entrypoint.sh from Windows CRLF to Unix LF (required on Windows hosts).
# libzip-dev enables the PHP zip extension so Composer can download packages as ZIPs (much faster).
# opcache is installed here to boost PHP execution speed by caching bytecode in RAM.
RUN apt-get update && apt-get install -y \
        dos2unix \
        git \
        iproute2 \
        libzip-dev \
        mariadb-client \
        unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip opcache \
    && docker-php-ext-enable pdo_mysql opcache \
    && rm -rf /var/lib/apt/lists/*

# Use the production configuration for better performance and security.
# This disables detailed error reporting to users but speeds up the engine.
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Copy our custom performance tweaks (OPcache settings, memory limits) into the PHP config directory.
COPY docker/php/optimized.ini $PHP_INI_DIR/conf.d/

# Install Composer via official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy entrypoint and fix line endings for Linux compatibility
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN dos2unix /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

RUN mkdir /var/www/html/CharitySwimRun
COPY . /var/www/html/CharitySwimRun

WORKDIR /var/www/html/CharitySwimRun

RUN composer install --optimize-autoloader

RUN chown -R www-data: /var/www/html/CharitySwimRun

RUN a2enmod rewrite

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]

EXPOSE 80
