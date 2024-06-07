cp /home/site/wwwroot/nginx /etc/nginx/sites-available/default

pkill -o -USR2 php-fpm

service nginx reload

cd /home/site/wwwroot

php bin/console cache:clear
