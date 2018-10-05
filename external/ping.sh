#!/bin/bash

apt-get update && apt-get upgrade

apt-get install nginx screen htop curl nano software-properties-common python-software-properties

LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php

apt-get update

apt-get install php7.0-fpm php7.0-json php7.0-curl

mkdir /var/www && mkdir /var/www/ping && chmod -R 777 /var/www/

rm /etc/nginx/sites-available/default

nano /etc/nginx/sites-available/default

server {
        listen 80;
        listen [::]:80 ipv6only=on;

        root /var/www/ping;
        index index.php index.html index.htm;

        server_name ping-us1.telldog.com;

        location / {
                try_files $uri $uri/ =404;
        }


        location ~ \.php$ {
                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
                fastcgi_index index.php;
                include fastcgi_params;
        }
}

service nginx restart