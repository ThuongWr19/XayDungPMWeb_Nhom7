# 1. Chọn base image là PHP 8.2 kèm theo máy chủ web Apache
FROM php:8.2-apache

# 2. Bật module rewrite của Apache (bắt buộc để các route của Laravel hoạt động)
RUN a2enmod rewrite

# 3. Đặt thư mục làm việc mặc định trong container
WORKDIR /var/www/html

# 4. Cài đặt các thư viện hệ thống cần thiết (GD, Zip, Unzip, Git...)
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip

# 5. Cài đặt Composer vào bên trong container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copy toàn bộ mã nguồn dự án của bạn vào thư mục làm việc của container
COPY . /var/www/html

# 7. Chạy lệnh cài đặt các package của Laravel (Lưu ý: dùng install, không dùng update)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 8. Cấu hình DocumentRoot của Apache trỏ thẳng vào thư mục /public của Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 9. Phân quyền cấp phép ghi (write permissions) cho các thư mục quan trọng của Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run migrations and then start the server
# The --force flag is required to run migrations in production mode
CMD php artisan migrate --force && php-fpm
# 10. Mở port 80 để Render có thể giao tiếp với ứng dụng
EXPOSE 80
