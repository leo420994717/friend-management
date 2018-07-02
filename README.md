# Friend Management App
This is a simple Lumen API service for friend management. It uses docker-compose to setup the app services. 

## Installation Steps

### Steps 1: Clone this repo
Before you start make sure you have [Docker Compose](https://docs.docker.com/compose/install/) installed on your machine.

Clone the repo by running the following command
```bash
git clone https://github.com/leo420994717/friend-management.git
```  

### Steps 2: Install dependencies
Create a throw-away container by executing the following command.

```bash
cd ./fm-service
docker run --rm -v $(pwd):/app composer/composer install
```

## Build & Run
Under app root folder, run following code to start docker-compose
```bash
docker-compose up -d
```
Create database tables
```bash
docker exec -it fm-service /bin/bash
cd /var/www/html
php artisan migrate
```
## Test
