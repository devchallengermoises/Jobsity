#!/bin/sh

# Start containers
docker compose up -d

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
sleep 10

# Copy .env file and run setup commands inside the container
docker exec jobsity-slim-1 bash -c "
    cp -R .env.sample .env &&
    composer install &&
    composer dump-autoload &&
    vendor/bin/phinx migrate &&
    vendor/bin/phinx seed:run -s UserSeeder
"

echo "Setup completed! Your application is ready at http://localhost:8080"
