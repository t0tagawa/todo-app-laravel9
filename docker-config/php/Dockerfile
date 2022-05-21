FROM php:8.1-fpm
WORKDIR /var/www
ADD . /var/www
RUN chown -R www-data:www-data /var/www

COPY php.ini /usr/local/etc/php/

# install composer
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# install packages
RUN apt-get update && \
  apt-get -y install --no-install-recommends npm libzip-dev libicu-dev libonig-dev libmcrypt-dev git unzip vim mariadb-client curl gnupg openssl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* && \
  docker-php-ext-install intl pdo_mysql zip bcmath mbstring mysqli

# install stable node and latest npm
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash
RUN apt-get install -y nodejs
# RUN apt-get install -y npm
RUN npm install -g n
RUN n stable
RUN npm update -g npm
