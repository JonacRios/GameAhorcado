# Use an official PHP runtime as a parent image
FROM php:8.2-fpm

# Set the working directory to /var/www
WORKDIR /var/www

# Copy the current directory contents into the container at /var/www
COPY . /var/www

# Install any needed packages specified in composer.json
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Make port 8000 available to the world outside this container
EXPOSE 8000

# Define environment variable
ENV NAME laravel-app

# Run php artisan serve when the container launches
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
