# References: 
#   - https://github.com/dunglas/symfony-docker
#   - https://github.com/jwilder/dockerize

FROM php:8.1-fpm-alpine

RUN apk add --no-cache \
		acl \
		fcgi \
		file \
		gettext \
		git \
        openssl \
        postgresql-dev \
	;

ARG APCU_VERSION=5.1.21
RUN set -eux; \
	apk add --no-cache --virtual .build-deps \
		$PHPIZE_DEPS \
		icu-dev \
		libzip-dev \
		zlib-dev \
	; \
	\
	docker-php-ext-configure zip; \
	docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
		intl \
		zip \
	; \
	pecl install \
		apcu-${APCU_VERSION} \
	; \
	pecl clear-cache; \
	docker-php-ext-enable \
		apcu \
		opcache \
	; \
	\
	runDeps="$( \
		scanelf --needed --nobanner --format '%n#p' --recursive /usr/local/lib/php/extensions \
			| tr ',' '\n' \
			| sort -u \
			| awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
	)"; \
	apk add --no-cache --virtual .phpexts-rundeps $runDeps; \
	\
	apk del .build-deps

# Setup PHP config
# RUN sed -i s/\;extension=pdo_pgsql/extension=pdo_pgsql/g $PHP_INI_DIR/php.ini-development && \
RUN ln -s $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini
COPY .docker/startup/symfony/symfony.dev.ini $PHP_INI_DIR/conf.d/symfony.ini
COPY .docker/startup/php-fpm/zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf


WORKDIR /usr/local/bin
# If not running on an amd64 system, please change to fit target architecture
ARG ARCH="amd64"
ENV ARCH ${ARCH}

# Add Dockerize to wait for DB initialization
ENV DOCKERIZE_VERSION v0.6.1
RUN wget https://github.com/jwilder/dockerize/releases/download/$DOCKERIZE_VERSION/dockerize-alpine-linux-${ARCH}-$DOCKERIZE_VERSION.tar.gz \
    && tar -C /usr/local/bin -xzvf dockerize-alpine-linux-${ARCH}-$DOCKERIZE_VERSION.tar.gz \
    && rm dockerize-alpine-linux-${ARCH}-$DOCKERIZE_VERSION.tar.gz

WORKDIR /srv/app
COPY . /srv/app
COPY .env.test .env

# Install commposer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY .docker/startup/test.docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

CMD ["/bin/sh", "-c", "/usr/local/bin/docker-entrypoint.sh"]
