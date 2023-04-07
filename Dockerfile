# Set the base image to the official PHP 7.4 image
FROM php:7.4

# Update the package lists
RUN apt-get update

# Install required packages
RUN apt-get install -y \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libmcrypt-dev \
    libcurl4-openssl-dev \
    libssl-dev \
    zlib1g-dev \
    libicu-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    nodejs \
    npm \
    default-mysql-client \
    && docker-php-ext-install \
        bcmath \
        curl \
        gd \
        intl \
        mbstring \
        mysqli \
        opcache \
        pdo \
        pdo_mysql \
        zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the project files to the working directory
COPY . .

# Install project dependencies
RUN composer install --no-interaction --no-ansi --no-progress --prefer-dist


# Expose port 8000 for Laravel development server
EXPOSE 8000


# Start Laravel development server
CMD php artisan serve --host=0.0.0.0 --port=8000
