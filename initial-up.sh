/* #!/bin/bash

cp .env.example .env
docker compose up -d
docker exec -it app-bank-core-v1 bash -c "composer install && php artisan key:generate && php artisan jwt:secret && php artisan migrate --seed"
