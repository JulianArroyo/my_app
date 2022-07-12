FROM php:7.4.30-fpm

ARG UID

# Create user with same permissions as host and some useful stuff
RUN adduser -u ${UID} --disabled-password --gecos "" appuser
RUN mkdir /home/appuser/.ssh
RUN chown -R appuser:appuser /home/appuser/
RUN echo "StrictHostKeyChecking no" >> /home/appuser/.ssh/config
RUN echo "alias sf=/appdata/www/bin/console" >> /home/appuser/.bashrc

# Install packages and PHP extensions
RUN apt update \
    # common libraries and extensions
    && apt install -y git acl openssl openssh-client wget zip sendmail wkhtmltopdf xvfb \
    && apt install -y libpng-dev zlib1g-dev libzip-dev libbz2-dev libgmp-dev libxml2-dev libtidy-dev libxslt1-dev libicu-dev \
    && docker-php-ext-install intl pdo zip bz2 calendar gettext gmp soap tidy xsl gd\
    # for MySQL
    && docker-php-ext-install pdo_mysql \
    # for Postgres
    && apt install -y libpq-dev \
    && docker-php-ext-install pdo_pgsql \
    # for RabbitMQ and XDEBUG
    && docker-php-ext-install bcmath sockets \
    && apt install -y librabbitmq-dev vim libssh-dev \
    && pecl install xdebug amqp-1.11.0 \
    # enable Docker extensions
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache xdebug amqp bcmath bz2 calendar gettext gmp intl soap tidy xsl zip pdo_mysql pdo_pgsql gd

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
#RUN composer install --no-interaction

## Install and update composer
#RUN curl https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
#RUN composer self-update

# Install PHP-CS-FIXER
RUN wget https://cs.symfony.com/download/php-cs-fixer-v3.phar -O php-cs-fixer
RUN chmod a+x php-cs-fixer
RUN mv php-cs-fixer /usr/local/bin/php-cs-fixer

## Install Symfony binary
RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list
RUN apt update \
    && apt install -y symfony-cli

## Install Symfony binary (DEPRECATED)
#USER appuser
#RUN wget https://get.symfony.com/cli/installer -O - | bash
#USER root
#RUN mv /home/appuser/.symfony/bin/symfony /usr/local/bin/symfony

RUN mkdir -p /appdata/www

# Config XDEBUG
COPY ./xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /appdata/www