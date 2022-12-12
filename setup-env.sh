#!/bin/sh
cp -R .env.sample .env &&
docker compose up -d &&
docker exec jobsity-slim-1 bash -c "composer install; composer dump-autoload; vendor/bin/phinx migrate; vendor/bin/phinx seed:run -s UserSeeder; exit"
