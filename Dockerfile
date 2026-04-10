FROM php:8.3-apache

# システム依存パッケージ
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    zip unzip git curl \
    && docker-php-ext-install pdo pdo_pgsql mbstring gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs && apt-get clean

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache: DocumentRootをpublicに設定
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf && \
    sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf && \
    a2enmod rewrite && \
    a2dismod mpm_event mpm_worker 2>/dev/null || true && \
    a2enmod mpm_prefork

WORKDIR /var/www/html

COPY . .

RUN composer install --optimize-autoloader \
    && npm ci \
    && npm run build \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 80

CMD ["docker-entrypoint.sh"]
