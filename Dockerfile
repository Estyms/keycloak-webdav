FROM php:8.3-fpm-alpine

RUN apk add --no-cache php-xml php-curl php-pear

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del pcre-dev ${PHPIZE_DEPS} \
  && rm -rf /tmp/pear

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir /app

WORKDIR /app

COPY . .

RUN composer update

ENTRYPOINT [ "php", "-S", "0.0.0.0:8080", "index.php" ]