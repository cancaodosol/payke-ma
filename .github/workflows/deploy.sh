git checkout master
git pull

php8.1 ~/bin/composer install
php8.1 artisan migrate
php8.1 artisan cache:clear
php8.1 artisan config:clear
php8.1 artisan route:clear
php8.1 artisan view:clear

npm run build