# Local PHP environment using Docker

## Prerequisites

- Download and install [Docker](https://www.docker.com/get-started/).
- Download and install [Node](https://nodejs.org/en).

## Setup

### Docker
- (Optional) Change the localhost port by changing `8080` in nginx's ports in `docker-compose.yml`, e.g., `- "1234:80"`.
- (Optional) Change the PHP version by updating the number in `FROM php:8.0-fpm` in `PHP.Dockerfile`.
- Create a folder called `app` at the root. This is where your PHP files will live. Follow the [PHP package skeleton](https://github.com/php-pds/skeleton) filesystem. At a minimum, create an `index.php` file under `/app/public/` as this is nginx's entry point.
- Run `docker-compose up -d --build`.
- Once running, go to `localhost:8000` (unless you've changed the nginx port, in which case, replace `8000` with that number) in your browser. You should now be able to access your PHP files from the `app` directory.

### env
This project requires env variables to work. In the `/app/config/` directory, duplicate and/or rename the `example.env` file to `.env` and fill in the values.

### npm
- In the command line run `npm install` and it will install all the dependencies from the `package.json` file.

#### esbuild
esbuild is used as the JavaScript bundler. JavaScript files should be created in the `/scripts/` root directory and each new file must be added to the `entryPoints` array.

```
entryPoints: [
    "./scripts/scrip1.js",
    "./scripts/scrip2.js",
    ...
],
```
For scripts to be used in the app, run `npm run build` where they will be bundled into the `/app/public/scripts/` directory.
