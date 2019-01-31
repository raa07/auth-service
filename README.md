# auth-service
Repository for register &amp; auth users.
Also track events to external analytic service.
# how to start
- install 
[docker](https://docs.docker.com/install/)( 
[mac](https://docs.docker.com/docker-for-mac/),
[windows](https://docs.docker.com/docker-for-windows/)
) with 
[docker-compose](https://docs.docker.com/compose/install/)
- make sure that **80** port in **127.0.0.1** interface is free
- in the project dir run the next commands:
```bash
mkdir config/jwt # For JWT Token support
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
docker-compose up -d
open http://localhost
```
In .env file JWT_PASSPHRASE must be equals your secret key.

### Install vendors
```bash
composer install
```


### Rebuild images & recreate containers
```bash
docker-compose build --no-cache
docker-compose up -d --force-recreate
```

### To enable hot code refreshing in container follow instructions in ./docker-compose.yml


### Run test
```bash
./test
```

### RabbitMQ management
[http://localhost:15672/](http://localhost:15672/) **[guest:guest]**

# Structure
Api structure:
```bash
api/users/new.json [POST] fields:
	nickname:string
	last_name:string
	first_name:string
	age:int
	password:string
Returns: ['success' => 'true', 'data' => ['message' => 'User created']

/api/analytics/new.json [GET] field:
	source_label:string
Returns: ['success' => 'true', 'data' => ['message' => 'hit recovered']]

/api/login/auth.json [POST] fields:
	nickname:string
	password:string
Returns: ['success' => 'true', 'data' => ['token' => $token]]
```
For auth using Authorization header.


