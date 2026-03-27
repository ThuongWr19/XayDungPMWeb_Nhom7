FROM php:8.2-fpm

# 1. Cài đặt thư viện hệ thống và extension PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip

# 2. Thiết lập thư mục làm việc
WORKDIR /var/www

# 3. Copy toàn bộ mã nguồn vào container (bao gồm cả composer.json)
COPY . .

# 4. Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 5. Bây giờ composer.json đã tồn tại trong /var/www, lệnh này sẽ chạy được
RUN composer install --no-dev --optimize-autoloader

# 6. Cấp quyền cho Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# 7. Khởi chạy
CMD php artisan migrate --force && php-fpm
