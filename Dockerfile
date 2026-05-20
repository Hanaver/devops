# 1. 选择 PHP 基础镜像
FROM php:8.5-fpm

# 2. 安装 Laravel 所需的系统依赖和 PHP 扩展 (此为基于实战经验的补充)
RUN apt-get update && apt-get install -y libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql zip

# 3. 将你的 PHP 应用代码复制到容器中 [3]
COPY . /var/www/html/

# 4. 修改目录权限确保 Web 服务器可以读写 storage 和 cache 目录 (此为基于实战经验的补充)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 5. 暴露 9000 端口供外部访问
EXPOSE 9000
