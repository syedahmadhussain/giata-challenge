APP_SERVICE_NAME=app
APP_SECRET := $(shell openssl rand -hex 32)
install: env build composer-install

env:
	cd "./app" && cp .env.dist .env && printf "\nAPP_SECRET=$(APP_SECRET)\n" >> .env

build:
	docker-compose up -d --build

composer-install:
	docker-compose run --rm app sh -c "composer install"

run:
	docker-compose up -d

stop:
	docker-compose down

test:
	docker-compose exec $(APP_SERVICE_NAME) sh -c "./vendor/bin/phpunit"


calculate-ratings:
	docker-compose exec $(APP_SERVICE_NAME) sh -c "sh calculate.sh"
