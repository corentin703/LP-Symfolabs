# Symfolabs

Branche de rendu : *main*.
Cloner comme ceci : ```git clone --branch main git@gitlab.iut-clermont.uca.fr:coverot/symfolabs.git```

## Mise en place :

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

## Fonctionnalités implémentées : 

- CRUD sur 
  - Promotions
  - Bon plans
  - Utilisateurs
  - Type de promotion
- Route REST sur ```GET /api/promotion``` pour obtenir la liste des promotions
- Gestion droits d'accès aux pages via système de rôles
- Gestion des badges à partir de règles définies en base de données (pas d'interface graphique pour les gérer)
- Gestion des températures des promotions
- Système de commentaire
- Système de signalement par courriel
- Gestion des favoris utilisateur
- Barre de recherche sur les promotions et les bons plans
- Statistiques (accessibles dans la rubrique *Mon compte* :
  - Nombre de deals postés
  - Nombre de commentaires postés
  - Note du deal posté le plus hot

## Fonctionnalités non disponibles :
- Joli design
- Page d'accueil
- Parmi les statistiques (TP 8)
  - Moyenne des notes des deals postés sur 1 an glissant
  - Pourcentage de deals postés devenus hot
