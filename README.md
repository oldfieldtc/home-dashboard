# Home kiosk/dashboard

A calendar and tasks/chores dashboard for home use. Running with Docker, it's built with PHP and uses the Paprika, Vikunja, and Google Calendar APIs to generate a calendar of the upcoming week as well as a list of tasks that need to be done every week/month.

## Prerequisites

- Download and install [Docker](https://www.docker.com/get-started/).
- Download and install [Node](https://nodejs.org/en).

## Setup

### Docker
- (Optional) Change the localhost port by changing `8080` in nginx's ports in `docker-compose.yml`, e.g., `- "1234:80"`.
- (Optional) Change the PHP version by updating the number in `FROM php:8.0-fpm` in `PHP.Dockerfile`.
- Create the `app` directory at the root. This is where your PHP files will live. Follow the [PHP package skeleton](https://github.com/php-pds/skeleton) filesystem. At a minimum, create an `index.php` file under `/app/public/` as this is nginx's entry point.
- `cd` into the `app` directory and run `php composer.phar update`. This will create the `vendor` directory which contains the project's dependencies.
- Run `docker-compose up -d --build`.
- Once running, go to `localhost:8000` (unless you've changed the nginx port, in which case, replace `8000` with that number) in your browser. You should now be able to access your PHP files from the `app` directory.

### env
This project requires env variables to work. In the `/app/config/` directory, duplicate and/or rename the `example.env` file to `.env` and fill in the values.

You will need accounts for [Paprika](https://www.paprikaapp.com/) and [Vikunja](https://vikunja.io/) (can also be self-hosted), 
as well as the API URL for your bin data (the most important one!).

#### Google Calendar
To use the Google Calendar API for this project, you will need to create a service account and generate the JSON private key.
Follow the '[Accessing Google APIs using Service account in Node.JS](https://web.archive.org/web/20230927185503/https://isd-soft.com/tech_blog/accessing-google-apis-using-service-account-node-js/)' walkthrough and rename the file to `calendar-private-key.json`
once downloaded and place in the `/app/config/` directory.

### npm
In the command line run `npm install` and it will install all the dependencies from the `package.json` file.

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
