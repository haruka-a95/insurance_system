FROM php:8.1.14-apache

# php.iniをコピー
COPY ./php.ini /usr/local/etc/php/

# composer インストール (マルチステージビルド)
COPY --from=composer:2.5.1 /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    vim \
    zip \
    curl \
    # 一旦古いnode/npm削除（あれば）
    && apt-get remove -y nodejs npm \
    && apt-get autoremove -y \
    # Node.js 16の公式セットアップスクリプトを実行してインストール
    && curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean

# PHP拡張とapache設定
RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -i 's!/var/www/html!/var/www/root/public!g' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/root
