To run this old poop:

clone it

enter file

run:

cp .env.example .env

php7.3 -r "copy('https://getcomposer.org/download/2.2.26/composer.phar', 'composer.phar');"

php7.3 composer.phar install --no-scripts

php7.3 composer.phar update laravel/framework --with-dependencies

php7.3 artisan key:generate

after that, "php artisan" should work
