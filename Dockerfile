FROM alpine:latest

LABEL Maintainer="Tomas Doubek <thomas.doubek@gmail.com>" \
      Description="Streamsenger chat app"

RUN apk --update add ca-certificates
RUN apk --no-cache add php php-fpm nginx supervisor curl

#configs
COPY docker/app.conf /etc/nginx/nginx.conf
COPY docker/fpm-pool.conf /etc/php81/php-fpm.d/www.conf
COPY docker/php.ini /etc/php81/conf.d/custom.ini
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

#perms
RUN mkdir -p /var/www/html
RUN chown -R nobody.nobody /var/www/html && \
  chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/log/nginx

USER nobody

WORKDIR /var/www/html

#copy code
COPY --chown=nobody src/ /var/www/html/src
COPY --chown=nobody storage/ /var/www/html/storage
COPY --chown=nobody www/ /var/www/html/www

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]