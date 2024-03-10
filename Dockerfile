FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install Redis extension
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY app /var/www

EXPOSE 9000

CMD ["php-fpm"]
