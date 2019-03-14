FROM php:5.6-fpm

RUN apt-get update
RUN apt-get install -y git wget

# extensions
RUN docker-php-ext-install mbstring
RUN apt-get install zlib1g-dev -y && docker-php-ext-install zip

# xdebug
RUN pecl install xdebug-2.5.5 && docker-php-ext-enable xdebug

# php.ini
COPY php.ini /usr/local/etc/php/

# www.conf
COPY www.conf /usr/local/etc/php-fpm.d/

# install composer
COPY install-composer.sh /tmp/install-composer.sh
RUN chmod +x /tmp/install-composer.sh
RUN bash /tmp/install-composer.sh
VOLUME /root/.composer
