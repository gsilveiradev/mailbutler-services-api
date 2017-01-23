# API Example

Welcome to my example api built with Laravel and Docker dev environment with laradocker!

This RESTful API use the api versioning concept.

# Install

Follow the steps to start and configure the project:

## 1) Download Docker

Install Docker Toolbox: [Download](https://www.docker.com/products/docker-toolbox)

## 2) Configure Host IP

Because I am inside a network by IP 192.168.99.*, I needed to change the default Ip Address:

Open and edit the file: ```~/.docker/machine/machines/default/config.json```

```
#!json

"IPAddress": "192.168.150.100",
"HostOnlyCIDR": "192.168.150.1/24",
```

## 3) Clone project

Clone the project inside your local repo directory (--recursive option is used to clone the submodules too):

```
git clone --recursive https://github.com/guissilveira/laravel-api-example.git
```

## 4) Run docker and start the containers

- Start the docker machine: ```docker-machine start default```
- Execute: ```eval $(docker-machine env default)``` (You need to do this every time you start your docker-machine)
- Go inside the laradock directory: ```cd laradock```
- Up the docker containers: ```docker-compose up -d nginx mysql```

## 5) Configure the project

- Go inside the container (ssh) workspace to configure the project: ```docker-compose exec workspace bash```
- Run composer install: ```composer install```
- Configure your ```.env``` file (copy from ```.env.example```).
- Generate your application key: ```php artisan key:generate```
- Run migrations and Seed: ```php artisan migrate:refresh --seed```

## 6) Oh yeah!

Kudos! :clap:

Your project is running on: http://192.168.150.100

# Authentication Example

## Login

Send a POST to ```http://192.168.150.100/api/v1/authentication/``` using e-mail=user@user.com and password=password params.

## Other routes

See all routes file to test other available routes: ```routes/api.php```.

# Interest stuff

## Commands

Start and pause the virtual machine (default):

```
docker-machine start default
docker-machine stop default
```

List virtual machines:

```
docker-machine ls
```

Result:

```
NAME      ACTIVE   DRIVER       STATE     URL                          SWARM   DOCKER    ERRORS
default   *        virtualbox   Running   tcp://192.168.150.100:2376           v1.12.6
```

Up containers with nginx and postgres:

```
docker-compose up -d nginx postgres
```

List containers:

```
docker-compose ps
```

Result:
```
         Name                        Command               State                     Ports
------------------------------------------------------------------------------------------------------------
laradock_applications_1   /true                            Exit 0
laradock_nginx_1          nginx                            Up       0.0.0.0:443->443/tcp, 0.0.0.0:80->80/tcp
laradock_php-fpm_1        php-fpm                          Up       9000/tcp
laradock_postgres_1       /docker-entrypoint.sh postgres   Up       0.0.0.0:5432->5432/tcp
laradock_workspace_1      /sbin/my_init                    Up       0.0.0.0:2222->22/tcp
```

Go inside the containers (workspace container in this case):

```
docker-compose exec workspace bash
```

## Laradock

Laradock is a [Docker](https://www.docker.com/) PHP development environment. It facilitate running PHP Apps on Docker.

More information [here](https://github.com/laradock/laradock).