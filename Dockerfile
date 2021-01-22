FROM php:7-cli-alpine

WORKDIR /app

COPY . .

CMD ["php", "bin/console", "app:tsp"]
