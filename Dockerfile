FROM php:8.0-cli

ARG PROJECT_DIR="/var/www"
ENV PROJECT_DIR=${PROJECT_DIR}

WORKDIR ${PROJECT_DIR}

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN alias composer="php -n /usr/local/bin/composer"

# Install vim
RUN apt-get update && apt-get install -y vim

# Install dependencies
RUN apt-get install -y \
      zlib1g-dev \
      libzip-dev \
      unzip
RUN docker-php-ext-install zip

# Coping files into container
COPY . ${PROJECT_DIR}
