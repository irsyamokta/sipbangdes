# =====================
# STAGE 1: Frontend
# =====================
FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY resources resources
COPY public public
COPY vite.config.* tsconfig.json ./

RUN npm run build


# =====================
# STAGE 2: App
# =====================
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    nginx \
    bash \
    chromium \
    nss \
    freetype \
    harfbuzz \
    ttf-freefont \
    nodejs \
    npm \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql

# Set nginx upload limit
RUN sed -i 's/client_max_body_size 1m;/client_max_body_size 20M;/g' /etc/nginx/nginx.conf

# Set PHP upload limit
RUN echo "upload_max_filesize=20M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=20M" >> /usr/local/etc/php/conf.d/uploads.ini

# Puppeteer config
ENV CHROME_PATH=/usr/bin/chromium
ENV PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium
ENV PUPPETEER_SKIP_CHROMIUM_DOWNLOAD=true

# Install puppeteer
RUN npm install -g puppeteer --unsafe-perm=true

# Create puppeteer cache folder
RUN mkdir -p /home/www-data/.cache/puppeteer \
    && chown -R www-data:www-data /home/www-data

WORKDIR /var/www/html

COPY . .

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

COPY --from=frontend /app/public/build public/build

COPY nginx/default.conf /etc/nginx/http.d/default.conf

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
