FROM php:8.4-apache

ENV DB_SERVER=mysql
ENV DB_BENUTZER=my_db_user
ENV DB_PASSWORT=my_db_user_password

# Install system dependencies, PHP extensions, and tools in one layer to keep image size minimal.
# dos2unix converts entrypoint.sh from Windows CRLF to Unix LF (required on Windows hosts).
# libzip-dev enables the PHP zip extension so Composer can download packages as ZIPs (much faster).
RUN apt-get update && apt-get install -y \
        dos2unix \
        git \
        iproute2 \
        libzip-dev \
        mariadb-client \
        unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip \
    && docker-php-ext-enable pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

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
