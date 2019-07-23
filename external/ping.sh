#!/bin/bash

apt-get update && apt-get upgrade

apt-get install nginx screen htop curl nano software-properties-common python-software-properties

add-apt-repository ppa:ondrej/php

apt-get update

apt-get install php7.3-fpm php7.3-cli php7.3-json php7.3-curl

mkdir /var/www/ping && chmod -R 777 /var/www/

rm /etc/nginx/sites-available/default && touch /etc/nginx/sites-available/default

echo "server {" >> /etc/nginx/sites-available/default
echo "listen 80;" >> /etc/nginx/sites-available/default
echo "listen [::]:80 ipv6only=on;" >> /etc/nginx/sites-available/default
echo "root /var/www/ping;" >> /etc/nginx/sites-available/default
echo "index index.php index.html index.htm;" >> /etc/nginx/sites-available/default
echo "server_name $1 www.$1;" >> /etc/nginx/sites-available/default
echo "location / {" >> /etc/nginx/sites-available/default
echo "try_files $uri $uri/ =404;" >> /etc/nginx/sites-available/default
echo "}" >> /etc/nginx/sites-available/default
echo "location ~ \.php$ {" >> /etc/nginx/sites-available/default
echo "include snippets/fastcgi-php.conf;" >> /etc/nginx/sites-available/default
echo "fastcgi_pass unix:/var/run/php/php7.3-fpm.sock;" >> /etc/nginx/sites-available/default
echo "}" >> /etc/nginx/sites-available/default
echo "}" >> /etc/nginx/sites-available/default

service nginx restart
