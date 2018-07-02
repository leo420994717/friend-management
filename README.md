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
### Test with Postman
1. Import file ./postman/fm.postman_collection.json to postman
2. Change environment variable "HOST" to http://localhost or your local IP. (I'm using VM with local IP address 192.168.99.100)
3. You can send request to test now. Below is one example to create a new user: 
Send request to {{HOST}}/fm-service/users with below JSON input
```bash
{
	"email": "kate@example.com"
}
```
The API should return the following JSON response on success:
```bash
{
	"success": true
}
```
### Test with Phpunit
Add php unit test scripts to test with all 6 user stories. 
This unit test only can run one time, then need to rollback the data. Because test result will have conflict after adding more friends. 
To run the test: 
```bash
docker exec -it fm-service /bin/bash
cd /var/www/html
php artisan migrate:rollback
php artisan migrate
./vendor/bin/phpunit tests/FmTest.php
```

## About JSON responses for any errors
Please refer to the responseError function in the controllers under ./fm-service/app/Http/Contorllers

