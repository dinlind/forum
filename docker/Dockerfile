FROM php:7.3.9-fpm-alpine3.10
WORKDIR /var/www	

RUN apk update && \
	docker-php-ext-install pdo_mysql && \
	apk add php7-dev \
	build-base \
	php-gd \
	git \
	sudo && \
	rm -rf /var/cache/apk/* && \
	pecl install xdebug && \
	cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
	echo "extension=/usr/lib/php7/modules/gd.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` && \
	echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20180731/xdebug.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` && \
	curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer 
