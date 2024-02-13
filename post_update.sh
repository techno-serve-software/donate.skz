php artisan down --message='Maintenance In Progress' --retry=60

git pull origin main

composer install --no-dev

php artisan optimize

php artisan view:cache

php artisan up
