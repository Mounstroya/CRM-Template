#!/bin/sh
set -e

# wait for MySQL to accept connections
until php artisan migrate:status > /dev/null 2>&1; do
  echo "Waiting for database..."
  sleep 2
done

php artisan migrate --force

# only seed once: skip if clientes table already has data
COUNT=$(php artisan tinker --execute="echo \App\Models\Cliente::count();" 2>/dev/null | tail -1)
if [ "$COUNT" = "0" ] || [ -z "$COUNT" ]; then
  php artisan db:seed --force
else
  echo "Data already seeded ($COUNT clientes), skipping seeder. Run 'php artisan db:seed --force' manually to re-seed."
fi

php artisan serve --host=0.0.0.0 --port=8000
