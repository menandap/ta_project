# Use the base image with PHP and other necessary configurations
FROM php:8.2-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Enable Apache modules
RUN a2enmod rewrite

# Install necessary dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    curl \
    git \
    unzip \
    && docker-php-ext-install mysqli pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application files into the container
COPY app_files/ /var/www/html/

# Run Composer commands
RUN composer install --no-scripts --no-autoloader \
    && composer dump-autoload

# Clear cache and configuration
RUN php artisan cache:clear \
    && php artisan config:clear

# Generate the application key
RUN php artisan key:generate

# Configure Apache within the Dockerfile
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!/var/www/html/public!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Give appropriate permissions to Laravel directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
