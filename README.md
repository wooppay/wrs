# WRS (Wooppay Rating System)
The system simplifies the assessment of team performance. It makes the procedure for awarding awards transparent, creates conditions under which each participant understands what needs to be done in order to increase the efficiency of his work. One of the goals of the system is to increase the motivation of the team in the form of creating a competitive atmosphere.

## Install

Project was written on PHP with Symfony Framework. Just clone this project to start

```
git clone git@github.com:wooppay/wrs.git
```

You need to install the packages necessary for the system to work

```
composer install
```

WRS can be setted up in different ways

### Docker

The project can be run in a container using docker-compose

```
docker-compose up -d --build
```

In order for the system from the container to work normally with the database, you must specify the name of the service with the database in the `.env` configuration in the `DATABASE_URL` section as the host. You can find out the name of the service from the database by running the following command

```
docker-compose ps
```

As a result, the DB connection string will look something like this:

```
DATABASE_URL=pgsql://postgres:postgres@wrs_db_1:5432/wrs
```

If the work with the system will be carried out through a container, then migrations and fixtures must be launched in the container. In order to enter the container, you must run the following command

```
docker exec -ti wrs_web_1 bash
```

The container uses the Nginx web server with a pre-prepared configuration file for the virtual host. Server name:

```
server_name wrs.local
```

In order for the system to successfully open in the browser, it is necessary to write the following rule in the hosts of the client (not the container)

```
127.0.0.1 wrs.local
```

### Local server

You can also use your own local server like Nginx or Apache

### Symfony Server

The easiest way to launch a system without a container and Nginx or Apache is Symfony Local Web Server, for this you just need to run the following command:

```
bin / console server: start
```

In order to access the system, enter the address “http://127.0.0.1:8080” in the address bar of the browser

Database access is configured in the `.env` file located in the root of the project

To create the structure of tables in the database, you need to run migrations

```
php bin / console doctrine: migrations: migrate
```

Then fill the tables with test data

```
php bin / console doctrine: fixtures: load
```