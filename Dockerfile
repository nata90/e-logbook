FROM php:7.1-apache
RUN a2enmod rewrite
RUN docker-php-ext-install pdo_mysql
COPY . /var/www/html/elogbook
RUN mv /var/www/html/elogbook/config/db-docker.php /var/www/html/elogbook/config/db.php