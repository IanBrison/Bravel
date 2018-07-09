FROM php:7.2-apache
RUN  a2enmod rewrite
RUN sed -i -e "s/\/var\/www\/html/\/var\/www\/html\/web/" /etc/apache2/sites-available/000-default.conf
RUN sed -i -e "s/\/var\/www\/html/\/var\/www\/html\/web/" /etc/apache2/sites-available/default-ssl.conf
RUN docker-php-ext-install pdo pdo_mysql
