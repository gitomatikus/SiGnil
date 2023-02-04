required php 7.4


move laradock.env to laradock dir as .env

in laradock conteiners files:


nginx.conf change `client_max_body_size` to 400M
php.ini in fpm and laravel.ini change `upload_max_filesize = 400M` and  `post_max_size = 400M`, also give some memory in laravel.ini


docker-compose up -d nginx redis workspace



move laravel-echo-server.json to laradock echo server dir


docker-compose up laravel-echo-server


