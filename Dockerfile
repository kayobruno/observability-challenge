FROM php:7.4-fpm-alpine

RUN apk add --no-cache mysql-client bash
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www
RUN rm -rf /var/www/html

ADD . /var/www
RUN chown -R www-data:www-data /var/www

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN chmod +x ./.docker/scripts/composer.sh
ENTRYPOINT [ "bash", "./.docker/scripts/composer.sh" ]

EXPOSE 9000
CMD ["php-fpm"]