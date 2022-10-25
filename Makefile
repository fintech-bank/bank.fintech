.PHONY: install
install:
	composer install --no-interaction
	rm -rf .env
	cp .env.example .env

	php artisan key:generate
	php artisan migrate:fresh --seed
	php artisan storage:link
	chmod -R 777 storage/ bootstrap/
