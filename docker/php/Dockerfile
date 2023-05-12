FROM php:7.4.32-fpm

ARG UID

# Create user with same permissions as host and some useful stuff
RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "alias sf=/appdata/www/bin/console" >> /home/appuser/.bashrc

# Install packages and PHP extensions
RUN apt-get update \
    && apt upgrade -y \
    # common libraries and extensions
    && apt-get install -y vim ca-certificates apt-transport-https software-properties-common lsb-release \
    && apt-get install -y curl bzip2 zip unzip xz-utils gnupg git acl openssl openssh-client wget \
    && apt-get install -y libpng-dev zlib1g-dev libzip-dev libbz2-dev libgmp-dev libxml2-dev libtidy-dev libxslt1-dev libicu-dev \
    && docker-php-ext-install -j "$(nproc)" bcmath bz2 calendar gettext gmp intl soap tidy xsl zip pdo gd \
    # for MySQL
    && docker-php-ext-install -j "$(nproc)" pdo_mysql \
    # for Postgres
    && apt-get install -y libpq-dev \
    && docker-php-ext-install -j "$(nproc)" pdo_pgsql \
    # for RabbitMQ and XDEBUG
    && docker-php-ext-install -j "$(nproc)" sockets \
    && apt-get install -y librabbitmq-dev libssh-dev \
    # https://chrisshennan.com/blog/fixing-pecl-xdebug-requires-php-version-8-installed-version-7-4
    # https://stackoverflow.com/questions/75029280/install-xdebug-for-php-7-4-on-m1-apple
    # https://xdebug.org/docs/compat
    && pecl install xdebug-3.1.6 amqp-1.11.0 \
    # enable Docker extensions
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug amqp bcmath bz2 calendar gettext gmp intl soap tidy xsl zip pdo_mysql pdo_pgsql gd \
    && echo "Done."

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#RUN composer install --no-interaction

## Install and update composer
#RUN curl https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
#RUN composer self-update

# Install NODE 14 and Yarn
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt -y install nodejs
RUN curl -sL https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add -
RUN echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list
RUN apt update && apt install yarn

# Install PHP-CS-FIXER
RUN wget https://cs.symfony.com/download/php-cs-fixer-v3.phar -O php-cs-fixer
RUN chmod a+x php-cs-fixer
RUN mv php-cs-fixer /usr/local/bin/php-cs-fixer

## Install Symfony binary
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash
RUN apt update \
    && apt install -y symfony-cli

## Install Symfony binary (DEPRECATED)
#RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
#RUN apt update \
#    && apt install -y symfony-cli

## Install Symfony binary (DEPRECATED)
#USER appuser
#RUN wget https://get.symfony.com/cli/installer -O - | bash
#USER root
#RUN mv /home/appuser/.symfony/bin/symfony /usr/local/bin/symfony

RUN mkdir -p /appdata/www

# Config XDEBUG
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /appdata/www
