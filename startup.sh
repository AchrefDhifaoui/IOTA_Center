cp /home/site/wwwroot/azure/nginx /etc/nginx/sites-available/default
cp /home/site/wwwroot/azure/php.ini /usr/local/etc/php/php.ini

pkill -o -USR2 php-fpm

service nginx reload

cd /home/site/wwwroot

php bin/console cache:clear
