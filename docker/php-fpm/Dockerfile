FROM php:7.3-fpm-buster
SHELL ["/bin/bash", "-oeux", "pipefail", "-c"]

# timezone environment
ENV TZ=UTC \
  # locale
  LANG=en_US.UTF-8 \
  LANGUAGE=en_US:en \
  LC_ALL=en_US.UTF-8 \
  # composer environment
  COMPOSER_ALLOW_SUPERUSER=1 \
  COMPOSER_HOME=/composer

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer
# multi state build 容量を削減するための小技

RUN apt-get update && \
  apt-get -y install git libicu-dev libonig-dev libzip-dev libpq-dev 	libjpeg-dev libpng-dev unzip locales && \
  apt-get clean && \
  rm -rf /var/lib/apt/lists/* && \
  locale-gen en_US.UTF-8 && \
  localedef -f UTF-8 -i en_US en_US.UTF-8 && \
  mkdir /var/run/php-fpm && \
  docker-php-ext-install intl pdo_mysql zip bcmath gd && \
  docker-php-ext-install pdo_pgsql zip && \
  docker-php-ext-configure \
  gd --with-png-dir=/usr/include --with-jpeg-dir=/usr/include && \
  composer config -g process-timeout 3600 && \
  composer config -g repos.packagist composer https://packagist.org

COPY ./docker/php-fpm/zzz-www.conf /usr/local/etc/php-fpm.d/zzz-www.conf
COPY ./docker/php-fpm/php.ini /usr/local/etc/php/php.ini

WORKDIR /work