FROM php:8.2-fpm

ARG UID=1001
ARG GID=100

# Crear usuario igual a Jenkins
# RUN useradd -u ${UID} -g ${GID} -m appuser
RUN getent group ${GID} \
    || groupadd -g ${GID} appgroup \
    && useradd -u ${UID} -g ${GID} -m appuser

# Dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    git unzip curl zip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev \
    libicu-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        gd intl zip mbstring bcmath pdo_pgsql pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo pdo_mysql zip

# Timezone
ENV TZ=America/La_Paz
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Carpetas necesarias para Laravel
RUN mkdir -p storage bootstrap/cache \
    && chown -R appuser:users /var/www
#lo de arriba a prod
# Usuario final
USER appuser
