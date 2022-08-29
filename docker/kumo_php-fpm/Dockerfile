FROM ubuntu:22.04

MAINTAINER maintainer="Hely Suarez Marin <helysm@gopenux.com>"

ENV DEBIAN_FRONTEND noninteractive

################ Section BUILD TOOLS ################
RUN \
  sed -i 's/# \(.*multiverse$\)/\1/g' /etc/apt/sources.list && \
  apt-get update && \
  apt-get -y upgrade && \
  apt-get install -y build-essential && \
  apt-get install -y software-properties-common && \
  apt-get install -y \
    supervisor \
    curl \
    htop \
    wget \
    unzip \
    git \
    nano && \
  rm -rf /var/lib/apt/lists/*
################ Section BUILD TOOLS ################

################ Section SUDO ################
RUN \
  apt-get update && \
  apt-get -y install \
    sudo && \
  rm -rf /var/lib/apt/lists/*
################ Section SUDO ################

################ Section USER ################
RUN \
  sed -i '/secure_path/d' /etc/sudoers && \
  sed -i '/%sudo/d' /etc/sudoers

RUN \
  sudo useradd kumo --shell /bin/bash --create-home -u 1000 && \
  sudo usermod -a -G sudo kumo && \
  echo '%sudo ALL = (ALL) NOPASSWD:ALL' >> /etc/sudoers && \
  echo 'kumo:docker' | chpasswd

USER kumo

RUN mkdir -p /home/kumo/.ssh
COPY --chown=kumo:kumo authorized_keys /home/kumo/.ssh/

USER root
################ Section USER ################

################ Section PHP ################
RUN \
  apt-get update && \
  apt-get -y upgrade && \
  apt-get install -y \
    language-pack-en-base && \
  rm -rf /var/lib/apt/lists/*

ENV LC_ALL=en_US.UTF-8
ENV LANG=en_US.UTF-8

RUN mkdir /var/run/php

RUN \
  add-apt-repository -y ppa:ondrej/php

RUN apt-get update && \
    apt-get install -y \
        php8.1 \
        php8.1-dev \
        php8.1-cli \
        php8.1-fpm \
        php8.1-opcache \
        php8.1-mbstring \
        php8.1-cgi \
        php8.1-curl \
        php8.1-intl \
        php8.1-zip \
        php8.1-xml \
        php8.1-xmlreader \
        php8.1-gd \
        php8.1-common \
        php8.1-pgsql \
        php8.1-mysql \
        php8.1-sqlite \
        php8.1-xdebug \
        php8.1-mongodb \
        mysql-client \
        nano \
        unzip \
        curl \
        openssh-server \
        libpq-dev && \
        rm -rf /var/lib/apt/lists/*
################ Section PHP ################

################ Section COPY FILES ################
COPY root /
################ Section COPY FILES ################

################ Section COMPOSER ################
RUN curl -sS https://getcomposer.org/download/latest-stable/composer.phar -o composer.phar
RUN mv composer.phar /usr/local/bin/composer
RUN chmod 755 /usr/local/bin/composer
################ Section COMPOSER ################

################ Section SSH ################
RUN rm -rf /var/lib/apt/lists/*
RUN mkdir /var/run/sshd
RUN sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd
################ Section SSH ################

ENV TERM xterm
ENV ON_ENTRY_SCRIPT=$ON_ENTRY_SCRIPT

WORKDIR /var/www

ENTRYPOINT ["/entrypoint.sh"]
