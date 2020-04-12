FROM php:7.3-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    zlib1g-dev \
    zip \
    unzip \
    curl \
    && rm -rf /var/lib/apt/lists/*


# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install opcache
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

# Install apcu
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Install xdebug
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

# Add aliases
RUN echo 'alias phpcs="php vendor/bin/phpcs"' >> ~/.bashrc
RUN echo 'alias phpcbf="php vendor/bin/phpcbf"' >> ~/.bashrc


WORKDIR /var/www/app
