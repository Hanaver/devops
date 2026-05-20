# 1. 选择 PHP 基础镜像
FROM php:8.5-fpm

# 2. 安装 Laravel 所需的系统依赖和 PHP 扩展 (此为基于实战经验的补充)
RUN apt-get update && apt-get install -y libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. 容器启动目录
WORKDIR /var/www/html/

# 4. 【关键步骤】先复制 composer.json 和 composer.lock
COPY composer.json composer.lock ./

# 5. 【执行命令】使用 RUN 在构建镜像时执行依赖安装
# 这里使用了资料中推荐的生产环境优化参数 [3]
RUN composer install --no-dev --optimize-autoloader --no-scripts

# 6. 将其余的业务代码复制到容器中
COPY . .

# 7. 【新增】代码复制完成后，手动补上 Laravel 的扩展包发现和性能优化脚本
RUN php artisan package:discover --ansi \
    && php artisan optimize

# 8. 修改目录权限确保 Nginx/PHP-FPM 有权读写
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. 暴露 9000 端口供外部访问
EXPOSE 9000
