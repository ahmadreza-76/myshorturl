create .env from .env.example
setup a database and set it in .env file
setup a redis database and set it in .env file
composer install
php artisan jwt:secret
php artisan migrate
php artisan queue:work
php artisan serve
