FROM php:7.4-apache
RUN apt-get update
RUN chmod 774 /var/www/html
COPY index.php /var/www/html
RUN docker-php-ext-install pdo pdo_mysql 
RUN docker-php-ext-install mysqli
RUN echo "DirectoryIndex index.html index.htm index.php welcome.html" >>../../../etc/apache2/apache2.conf
COPY users.sql /var/www/html
USER www-data:www-data
EXPOSE 80


