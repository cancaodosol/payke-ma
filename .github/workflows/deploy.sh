if [ -d .git ]; then
  git checkout master
  git pull
else
  git clone https://github.com/cancaodosol/docker-laravel-sail.git
fi

php8.1 ~/bin/composer install
php8.1 artisan migrate
php8.1 artisan cache:clear
php8.1 artisan config:clear
php8.1 artisan route:clear
php8.1 artisan view:clear
