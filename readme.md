To run with Docker:

docker compose up -d --build

docker compose exec app composer install

* docker compose exec app composer validate
* docker compose exec app composer audit

docker compose exec app php artisan migrate --seed



To delete everything from Docker:

docker compose down --volumes --rmi all





npm run build

npm run dev




git checkout -b upgrade/laravel-*
git add .
git commit -m "..."
git push -u origin upgrade/laravel-*
git checkout master
git merge upgrade/laravel-*
git push origin master
git checkout -b upgrade/laravel-* .....
