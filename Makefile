all: build migrate analyse test

PHONY: build migrate analyse test

build:
	docker-compose up -d --build slim
	docker-compose run --rm composer install

migrate:
	docker-compose run --rm --entrypoint vendor/bin/phinx composer migrate -n
	docker-compose run --rm --entrypoint vendor/bin/phinx composer migrate -n --environment=testing

analyse:
	docker-compose run --rm composer analyse

test:
	docker-compose run --rm composer test
