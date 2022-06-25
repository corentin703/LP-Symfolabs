#! /bin/bash

composer install
npm i
npm run build

php bin/console doctrine:database:create --if-not-exists
echo -e "yes\n" | php bin/console make:migration
echo -e "yes\n" | php bin/console doctrine:migration:migrate
echo -e "yes\n" | php bin/console doctrine:fixtures:load

echo "Mise en place terminée !"
