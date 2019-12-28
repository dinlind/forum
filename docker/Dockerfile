FROM php:7.3.9-fpm-alpine3.10
WORKDIR /var/www	

RUN apk update && \
	docker-php-ext-install pdo_mysql && \
	apk add php7-dev \
	build-base \
	openssl-dev \
	git \
	sudo && \
	rm -rf /var/cache/apk/* && \
	git clone https://github.com/mongodb/mongo-php-driver.git && \
	cd mongo-php-driver && \
	git submodule update --init && \
	phpize && \
	./configure --with-php-config=/usr/bin/php-config --with-mongodb-ssl=openssl && \
	make && \
	cp ./modules/mongodb.so /usr/lib/php7/modules && \
	pecl install xdebug && \
	cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini && \
	echo "extension=/usr/lib/php7/modules/mongodb.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` && \
	echo "zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20180731/xdebug.so" >> `php --ini | grep "Loaded Configuration" | sed -e "s|.*:\s*||"` && \
	curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer 
