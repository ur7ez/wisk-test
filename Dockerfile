FROM php:8.2-fpm-alpine3.16

# Install tools required for build stage
RUN apk add --update --no-cache \
    bash curl wget rsync ca-certificates openssl openssh git tzdata openntpd \
    libxrender fontconfig libc6-compat \
    mysql-client gnupg binutils-gold autoconf \
    g++ gcc libgcc linux-headers make py-pip

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer \
    && chmod 755 /usr/bin/composer

# Install additional PHP libraries
RUN docker-php-ext-install bcmath pdo pdo_mysql

# Install libraries for compiling GD, then build it
RUN apk add --no-cache freetype libpng libjpeg-turbo libwebp freetype-dev libpng-dev libjpeg-turbo-dev libwebp-dev \
    && docker-php-ext-configure gd \
            --enable-gd \
            --with-freetype=/usr/include/ \
            --with-jpeg=/usr/include/ \
            --with-webp=/usr/include/ \
    && nproc=$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1) \
    && docker-php-ext-install -j${nproc} gd \
    && apk del --no-cache freetype-dev libpng-dev libjpeg-turbo-dev libwebp-dev

# Add ZIP archives support
RUN apk add --update --no-cache zlib-dev libzip-dev \
    && docker-php-ext-install zip

# Install xDebug
RUN pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug

# Enable xDebug
ADD xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /app