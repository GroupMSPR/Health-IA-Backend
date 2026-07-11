# Production image: FrankenPHP (Caddy + PHP) instead of `php artisan serve`.
FROM dunglas/frankenphp:1-php8.4-alpine

# PHP extensions (install-php-extensions ships with the FrankenPHP image and
# pulls the needed system libs, e.g. libpq for the pgsql drivers).
RUN install-php-extensions \
        pdo_pgsql \
        pgsql \
        zip \
        pcntl \
        bcmath \
        intl \
        opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Install PHP dependencies first (better layer caching). The path-repository
# packages must be present before `composer install`.
COPY composer.json composer.lock ./
COPY packages/ ./packages/
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .

RUN composer run-script post-autoload-dump 2>/dev/null || true \
    && php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear

# Writable runtime directories.
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/Caddyfile /etc/frankenphp/Caddyfile

# The platform provides $PORT at runtime; default to 8000 for local runs.
ENV PORT=8000
EXPOSE 8000

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
