# Local PHP environment using Docker

## Prerequisites

- Download and install [Docker](https://www.docker.com/get-started/).

## Installation

- Clone this repo to your local machine. As this is just a template for running PHP in Docker, feel free to change the directory name to something of your choosing. Don't commit to this repo unless you are changing the Dockerfile or the `docker-compose.yml` file.
- (Optional) Update the mysql `envrionment` variables in `docker-compose.yml`.
- (Optional) Change the localhost port by changing `8080` in nginx's ports in `docker-compose.yml`, e.g., `- "1234:80"`.
- (Optional) Change the PHP version by updating the number in `FROM php:8.0-fpm` in `PHP.Dockerfile`.
- Create a folder called `app` at the root. This is where your PHP files will live.
- Run `docker-compose up -d`.
- Once running, go to `localhost:8000` (unless you've changed the nginx port, in which case, replace `8000` with that number) in your browser. You should now be able to access your PHP files from the `app` directory.
- For the database connection details, use the environment variables from the 'mysql' service in `docker-compose.yml`. The 'Database Host' should be the name of the service - 'mysql'. If you haven't changed the port, you can access PHPMyAdmin by visiting `localhost:8080`.
