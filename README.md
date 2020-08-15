create .env from .env.example <br>
setup a database and set it in .env file <br>
setup a redis database and set it in .env file <br>
composer install <br>
php artisan jwt:secret <br>
php artisan migrate <br>
php artisan queue:work <br>
php artisan schedule:run <br>
php artisan serve <br>
php artisan commands to update analytics manually (this command will work complete only if run everyday and everyhour)<br>
(it will only check necessary short urls that had been changed)<br>
php artisan analytic:hourly <br>
php artisan analytic:daily <br>

