# 使用官方轻量级的 Nginx Alpine 镜像，减小镜像体积并降低安全攻击面
FROM nginx:alpine

# 删除默认的nginx配置
RUN rm /etc/nginx/conf.d/default.conf

# 讲本地的nginx配置复制到容器中
COPY ./docker/nginx.conf /etc/nginx/conf.d/

# 将项目中public目录复制到Nginx容器中
# 这样 Nginx 可以直接处理静态资源（CSS/JS/图片）请求，无需经过 PHP 容器，大幅提升性能
COPY ./public /var/www/html/public

# 暴露80 端口供外部或未来的 Kubernetes Ingress 访问
EXPOSE 80

# 在前台运行nginx， 确保容器不会在启动后退出
CMD ["nginx", "-g", "deamon off;"]
