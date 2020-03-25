FROM php:7.1-fpm

RUN apt-get update && apt-get install -y \
        build-essential \
        vim \
        git-core \
        zip unzip \
        curl \
        libmagick++-dev \
		libfreetype6-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        libmcrypt-dev \
        libmagickcore-dev \
        libxslt1-dev \
        librabbitmq-dev \
        libicu-dev \
        libzip-dev \
        libpq-dev \
        supervisor \
        --no-install-recommends

RUN true \
	&& docker-php-ext-install -j$(nproc) iconv \
	&& docker-php-ext-install -j$(nproc) pgsql \
	&& docker-php-ext-install -j$(nproc) gd \
	&& docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
	&& docker-php-ext-install -j$(nproc) pdo_pgsql \
	&& docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) pcntl \
    && docker-php-ext-install -j$(nproc) opcache \
    && docker-php-ext-install -j$(nproc) sockets \
    && docker-php-ext-install -j$(nproc) bcmath \
	&& docker-php-ext-install xsl zip gd iconv intl opcache sockets mbstring \
	&& docker-php-ext-configure pdo_pgsql --with-pdo-pgsql \
	&& docker-php-source delete

RUN pecl channel-update pecl.php.net \
    && pecl install redis-5.0.0 \
	&& pecl install xdebug-2.5.5 \
	&& pecl install amqp-1.9.4 \
	&& pecl install imagick-3.4.4 \
	&& docker-php-ext-enable redis xdebug amqp imagick

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/*

# Installs composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN rm -rf /usr/local/etc/php-fpm.d
COPY ./pools /usr/local/etc/php-fpm.d
COPY ./php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./php.ini /usr/local/etc/php/
COPY ./docker-php-entrypoint /usr/local/bin/

# https://bugs.php.net/bug.php?id=66773
RUN echo "opcache.enable_cli=1" >> /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini
#RUN echo "date.timezone = Europe/Moscow" > /usr/local/etc/php/conf.d/docker-php-date-timezone.ini
#RUN echo "memory_limit = 512M" > /usr/local/etc/php/conf.d/php-custom.ini

WORKDIR /srv/dev.tourhunter.com

RUN usermod -u 33 www-data \
    && groupmod -g 33 www-data \
    && chown -R www-data:www-data ~www-data \
    && touch /usr/local/etc/php/conf.d/xdebug_remote.ini \
    && chmod 0777 /usr/local/etc/php/conf.d/xdebug_remote.ini

ENTRYPOINT ["docker-php-entrypoint"]

CMD ["php-fpm"]