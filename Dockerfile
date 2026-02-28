# Sử dụng phiên bản PHP 8.2 (Chuẩn cho Laravel 11/12)
FROM php:8.2-cli

# Cài đặt các thư viện lõi của hệ điều hành Linux
RUN apt-get update -y && apt-get install -y \
    unzip \
    zip \
    git \
    libmariadb-dev \
    libzip-dev

# Cài đặt các gói mở rộng của PHP (quan trọng nhất là pdo_mysql để sau này chọc vào Database)
RUN docker-php-ext-install pdo_mysql zip

# Tải Composer (công cụ quản lý thư viện của PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Đặt thư mục làm việc mặc định trong container là /app
WORKDIR /app

# Copy toàn bộ code Laravel của bạn từ máy tính vào trong container
COPY . .

# Chạy lệnh cài đặt các thư viện của Laravel (nằm trong file composer.json)
RUN composer install --optimize-autoloader --no-dev

# Lệnh cuối cùng: Khởi động server Laravel! 
# Render bắt buộc ứng dụng phải lắng nghe ở địa chỉ 0.0.0.0 và cổng do Render tự cấp ($PORT)
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
