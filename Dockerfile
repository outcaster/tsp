FROM php:7-cli-alpine

WORKDIR /app

COPY . .

RUN apk add --no-cache composer && composer install

CMD ["php", "bin/console", "app:tsp"]
