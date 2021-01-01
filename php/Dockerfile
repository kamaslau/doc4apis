# 参考 https://hub.docker.com/_/php
FROM php:fpm

# 更新包管理器，安装mysqli核心插件
RUN apt-get update \
  && apt-get -y --no-install-recommends install \
  iputils-ping \
  && apt-get autoremove -y \
  && docker-php-ext-install mysqli \
  #&& docker-php-ext-enable mysqli