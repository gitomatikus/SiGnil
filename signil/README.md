required php 7.4
move laradock.env to laradock dir as .env
docker-compose up -d nginx redis workspace

move laravel-echo-server.json to laradock echo server dir
docker-compose up laravel-echo-server
