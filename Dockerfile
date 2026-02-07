FROM php:8.4.17-apache

ENV DB_SERVER=mysql
ENV DB_BENUTZER=my_db_user
ENV DB_PASSWORT=my_db_user_password

COPY entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

# installiere composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && php composer-setup.php && php -r "unlink('composer-setup.php');" && mv composer.phar /usr/local/bin/composer

RUN mkdir /var/www/html/CharitySwimRun
COPY . /var/www/html/CharitySwimRun

WORKDIR /var/www/html/CharitySwimRun

RUN apt-get update && apt-get install git mariadb-client iproute2 -y
RUN docker-php-ext-install mysqli pdo pdo_mysql \
     && docker-php-ext-enable pdo_mysql

RUN composer update 
RUN composer install 

RUN chown -R www-data: /var/www/html/CharitySwimRun 

RUN a2enmod rewrite

ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]

EXPOSE 80
