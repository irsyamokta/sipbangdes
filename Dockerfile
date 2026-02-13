# =====================
# STAGE 1: Frontend
# =====================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

# COPY SEMUA YANG DIBUTUHKAN VITE
COPY resources resources
COPY public public
COPY vite.config.* tsconfig.json ./

RUN npm run build


# =====================
# STAGE 2: App (PHP + NGINX)
# =====================
FROM php:8.3-fpm-alpine

RUN apk add --no-cache nginx bash \
    && docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

# Copy seluruh source Laravel
COPY . .

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --no-interaction

# COPY HASIL BUILD VITE
COPY --from=frontend /app/public/build public/build

COPY nginx/default.conf /etc/nginx/http.d/default.conf

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
