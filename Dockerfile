FROM php:8.2-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./mailer /app
WORKDIR /app

RUN apt update
RUN apt install -y libzip-dev zip

RUN composer install --no-interaction --optimize-autoloader

CMD [ "php", "./mailer.php" ]