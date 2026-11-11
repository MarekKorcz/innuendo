To run this old poop locally:

clone it

enter file

run:

cp .env.example .env

php7.3 -r "copy('https://getcomposer.org/download/2.2.26/composer.phar', 'composer.phar');"

php7.3 composer.phar install --no-scripts

php7.3 composer.phar update laravel/framework --with-dependencies

php7.3 artisan key:generate

after that, "php artisan" should work locally




To run with Docker:

docker compose up -d --build

docker compose exec app composer install

docker compose exec app php artisan migrate --seed



To delete everything from Docker:

docker compose down --volumes --rmi all
