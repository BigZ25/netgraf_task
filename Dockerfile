# Dockerfile for the Laravel application
FROM php:8.1-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    vim \
    sudo \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite

# Copy the Laravel application code into the container
COPY . .

# Create a virtual host configuration file for Apache
# Here we set the DocumentRoot to the Laravel public folder
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    ServerName pet_app.localhost\n\
    ServerAlias *.pet_app.localhost\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>" >> /etc/apache2/sites-available/pet_app.conf

# Enable the virtual host
RUN a2ensite pet_app

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
