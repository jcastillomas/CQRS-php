ARG PHP_VERION=7.4.3

FROM nginx:1.17-alpine as nginx

ENV APP_ENV prod

WORKDIR /app

COPY etc/artifact/nginx/nginx.conf /etc/nginx/conf.d/default.conf

COPY --from=builder /app/public /app/public
