FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
        git unzip libzip-dev libpng-dev \
    && docker-php-ext-install pdo_mysql zip bcmath gd \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# UID 1000 = mismo usuario que Codespaces, así puedes editar los archivos de src/
RUN useradd -m -u 1000 dev
USER dev

WORKDIR /var/www/html