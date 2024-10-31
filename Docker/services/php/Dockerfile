FROM php:fpm

# Install necessary PHP extensions and tools
RUN apt-get update  \
    && apt-get install -y zip unzip git \
    && docker-php-ext-install pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory and install dependencies
WORKDIR /app
COPY composer.json /app/
RUN composer install

# Copy the rest of the application files
COPY . .