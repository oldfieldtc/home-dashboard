# Local PHP environment using Docker

## Prerequisites

- Download and install [Docker](https://www.docker.com/get-started/).

## Installation

- (Optional) Change the localhost port by changing `8080` in nginx's ports in `docker-compose.yml`, e.g., `- "1234:80"`.
- (Optional) Change the PHP version by updating the number in `FROM php:8.0-fpm` in `PHP.Dockerfile`.
- Create a folder called `public` at the root. This is where your PHP files will live. Follow the [PHP package skeleton](https://github.com/php-pds/skeleton) filesystem.
- Run `docker-compose up -d`.
- Once running, go to `localhost:8000` (unless you've changed the nginx port, in which case, replace `8000` with that number) in your browser. You should now be able to access your PHP files from the `public` directory.
