# Friend Management App
This is a simple Lumen API service for friend management. It uses docker-compose to setup the app services. 

## Installation Steps

### Steps 1: Clone this repo
Clone or download this repo to your local "friend-management" folder

### Steps 2: Install dependencies
Create a throw-away container by executing the following command.

```bash
cd ./fm-service
docker run --rm -v $(pwd):/app composer/composer install
```

## Build & Run
Under app root folder "friend-management", run following code to start docker-compose
```bash
docker-compose up -d
```
