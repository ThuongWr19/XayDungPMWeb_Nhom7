# Cài đặt các thư viện hệ thống cần thiết cho GD và các tiện ích khác
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip

# Sau đó mới đến các lệnh COPY và composer
COPY . .

# Khuyên dùng: Nên dùng 'composer install' thay vì 'composer update' trong Dockerfile 
# để đảm bảo tính nhất quán giữa môi trường dev và production
RUN composer install --no-interaction --optimize-autoloader --no-dev
