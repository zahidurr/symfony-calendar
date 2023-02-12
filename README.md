# Custom calendar function #

Test Task - Level 1 - Custom calendar function

## Source Code Path
**`app/src/Controller/Calendar.php`**

## Useful links

* docker installation - https://docs.docker.com/engine/install/
* docker-compose installation - https://docs.docker.com/compose/install/

## Set up the environment ###

1. RUN `docker-compose up --build -d`

2. Enter `calendar_php-fpm` container
    * RUN `docker exec -it calendar_php-fpm bash`
    * inside the container RUN `composer install`
