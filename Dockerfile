# Sử dụng PHP 8.2 FPM hoặc bản bạn đang dùng
FROM php:8.2-fpm

# Cài đặt các extension cần thiết cho MySQL
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_mysql gd

# Copy mã nguồn vào container
WORKDIR /var/www
COPY . .

# Cài đặt Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho thư mục storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Lệnh khởi chạy: Chạy migration trước khi start server
# --force là bắt buộc khi chạy ở môi trường production
CMD php artisan migrate --force && php-fpm
