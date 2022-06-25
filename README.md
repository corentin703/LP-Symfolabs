# Symfolabs

## Mise en place

### Sur *VDN* : 
Entrez `docker-compose up -d` à la racine du répertoire du projet dans la machine virtuelle Docker.
Le site sera accessible sur [localhost:5080](http://localhost:5080).

### Sur un PC externe : 
Entrez `docker compose -f ./docker-compose-externe.yml up -d` à la racine du répertoire du projet.
Le site sera accessible sur [localhost:80](http://localhost).

Quelque-soit la configuration, vous obtiendrez une erreur causée par l'absence des dépendances, et de la base de données.
Pour régler cela, nous vous fait un script *setUp.sh* exécutant dans l'ordre les commandes suivantes :
- ```composer install``` - Installation des dépendances composer
- ```npm i``` - Installation des dépendances Front-end
- ```npm run build``` - Génère un bundle de dépendances pour le front

- ```php bin/console doctrine:database:create --if-not-exists``` - Crée la base de donnée si elle n'existe pas
- ```php bin/console make:migration``` - Génère un fichier de migration à partir du code existant
- ```php bin/console doctrine:migration:migrate``` - Applique la migration sur la base de données
- ```php bin/console doctrine:fixtures:load``` - Charge les fixtures en base afin de ne pas partir d'une base vierge

Les données insérées par les fixtures sont générées aléatoirement par la librairie [FakerPhp](https://fakerphp.github.io),
sauf le mot de passe par défaut des utilisateurs qui est *123456789*.

Exécutez le script comme suit : ```docker exec web_lp-web ./setUp.sh```.
