FROM centos:8

#version defined
ARG  SWOOLE_VERSION
ARG  EASYSWOOLE_VERSION

#install libs
RUN yum install -y curl zip unzip  wget openssl-devel gcc-c++ make autoconf
#install php
RUN yum install -y php-devel php-openssl php-mbstring php-json
# composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer
# use aliyun composer
RUN composer config -g repo.packagist composer https://mirrors.aliyun.com/composer/

# swoole ext
RUN wget https://github.com/swoole/swoole-src/archive/v${SWOOLE_VERSION}.tar.gz -O swoole.tar.gz \
    && mkdir -p swoole \
    && tar -xf swoole.tar.gz -C swoole --strip-components=1 \
    && rm swoole.tar.gz \
    && ( \
    cd swoole \
    && phpize \
    && ./configure --enable-openssl \
    && make \
    && make install \
    ) \
    && sed -i "2i extension=swoole.so" /etc/php.ini \
    && rm -r swoole

# Dir
WORKDIR /easyswoole
# install easyswoole
RUN cd /easyswoole \
    && composer require easyswoole/easyswoole=${EASYSWOOLE_VERSION} \
    && php vendor/bin/easyswoole install

EXPOSE 9501
